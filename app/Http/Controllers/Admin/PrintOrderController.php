<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PrintOrderController extends Controller
{
    private const STATUS_MENUNGGU = 'Menunggu Antrean';
    private const PAYMENT_MINTA_BATAL = 'menunggu_persetujuan_batal';
    private const PAYMENT_DITOLAK = 'ditolak';

    private function guardAdmin()
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin terlebih dahulu.');
        }
        return null;
    }

    /**
     * Tampilkan semua pesanan jasa cetak
     */
    public function index(Request $request)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $allowedFilters = [
            self::STATUS_MENUNGGU,
            'diproses',
            'selesai',
            self::PAYMENT_MINTA_BATAL,
            self::PAYMENT_DITOLAK,
            'semua',
        ];
        $keyword = $request->get('search');
        $filter  = $request->get('filter', self::STATUS_MENUNGGU);
        if (!in_array($filter, $allowedFilters, true)) {
            $filter = self::STATUS_MENUNGGU;
        }

        $baseQuery = Order::query()
            ->where('item_type', 'jasa')
            ->where('status', '!=', 'dibatalkan')
            ->where(function ($q) {
                // Hanya tampilkan pesanan yang sudah ada bukti bayar DP
                // atau sudah dikonfirmasi (dp_diterima, lunas, sisa_dibayar)
                $q->whereIn('payment_status', ['dp_diterima', 'lunas', 'sisa_dibayar'])
                    ->orWhere(function ($sub) {
                        $sub->where('payment_status', 'menunggu_konfirmasi')
                            ->whereNotNull('bukti_bayar')
                            ->where('bukti_bayar', '!=', '');
                    })
                    ->orWhereIn('payment_status', [
                        self::PAYMENT_MINTA_BATAL,
                        self::PAYMENT_DITOLAK,
                    ]);
            });

        $orders = (clone $baseQuery)
            ->with('user')
            ->when($keyword, function ($q) use ($keyword) {
                $q->whereHas('user', function ($uq) use ($keyword) {
                    $uq->where('full_name', 'LIKE', '%' . $keyword . '%');
                })->orWhere('detail_pesanan', 'LIKE', '%' . $keyword . '%');
            })
            ->when($filter !== 'semua', function ($q) use ($filter) {
                if (in_array($filter, [self::PAYMENT_MINTA_BATAL, self::PAYMENT_DITOLAK], true)) {
                    $q->where('payment_status', $filter);
                    return;
                }

                if ($filter === self::STATUS_MENUNGGU) {
                    $q->where('status', self::STATUS_MENUNGGU)
                        ->whereNotIn('payment_status', [self::PAYMENT_MINTA_BATAL, self::PAYMENT_DITOLAK]);
                    return;
                }

                if ($filter === 'diproses') {
                    $q->where(function ($query) {
                        $query->where('status', 'diproses')
                            ->orWhere(function ($sub) {
                                $sub->where('status', 'selesai')
                                    ->where('payment_status', '!=', 'lunas');
                            });
                    });
                    return;
                }

                if ($filter === 'selesai') {
                    $q->where('status', 'selesai')
                        ->where('payment_status', 'lunas');
                    return;
                }

                $q->where('status', $filter);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        $counts = [
            'menunggu' => (clone $baseQuery)
                ->where('status', self::STATUS_MENUNGGU)
                ->where('payment_status', 'menunggu_konfirmasi')
                ->whereNotNull('bukti_bayar')
                ->where('bukti_bayar', '!=', '')
                ->count(),
            'diproses' => (clone $baseQuery)
                ->where(function ($query) {
                    $query->where('status', 'diproses')
                        ->orWhere(function ($sub) {
                            $sub->where('status', 'selesai')
                                ->where('payment_status', '!=', 'lunas');
                        });
                })
                ->count(),
            'pelunasan_sisa' => (clone $baseQuery)
                ->where('status', 'selesai')
                ->whereIn('payment_status', ['sisa_dibayar', 'menunggu_konfirmasi'])
                ->whereNotNull('bukti_bayar')
                ->where('bukti_bayar', '!=', '')
                ->count(),
            'selesai'  => (clone $baseQuery)
                ->where('status', 'selesai')
                ->where('payment_status', 'lunas')
                ->count(),
            'minta_batal' => (clone $baseQuery)->where('payment_status', self::PAYMENT_MINTA_BATAL)->count(),
            'ditolak'  => (clone $baseQuery)->where('payment_status', self::PAYMENT_DITOLAK)->count(),
            'semua'    => (clone $baseQuery)->count(),
        ];

        return view('admin.print-orders.index', compact('orders', 'keyword', 'filter', 'counts'));
    }

    /**
     * Update status pesanan cetak
     */
    public function updateStatus(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $request->validate([
            'status' => 'required|in:' . self::STATUS_MENUNGGU . ',diproses,selesai',
            'harga_final' => 'nullable|required_if:status,selesai|integer|min:1',
            'catatan_admin' => 'nullable|string|max:500',
        ], [
            'harga_final.required_if' => 'Harga final wajib diisi saat status diubah menjadi selesai.',
            'harga_final.integer' => 'Harga final harus berupa angka.',
            'harga_final.min' => 'Harga final minimal Rp 1.',
        ]);

        $order = Order::where('item_type', 'jasa')->findOrFail($id);

        if (in_array($request->status, ['diproses', 'selesai'], true) && !$order->isDepositConfirmed()) {
            return redirect()->back()->with('error', 'Pesanan belum menerima pembayaran awal (DP). Konfirmasi pembayaran awal terlebih dahulu sebelum memproses pesanan.');
        }

        $order->status = $request->status;

        if ($request->status === 'selesai' && $request->filled('harga_final')) {
            $order->total_harga = (int) $request->harga_final;
        }

        if ($request->filled('catatan_admin')) {
            $order->catatan = trim($request->catatan_admin);
        } elseif ($request->status === 'selesai' && $order->catatan) {
            $order->catatan = $order->catatan;
        }

        if ($request->status === 'selesai'
            && $order->payment_status === 'dp_diterima'
            && $order->getRemainingBalance() <= 0) {
            $order->payment_status = 'lunas';
        }

        $order->save();

        $message = $request->status === 'selesai'
            ? 'Pesanan ditandai selesai dikerjakan. Pelanggan dapat melunasi sisa pembayaran.'
            : 'Status pesanan berhasil diperbarui.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Batalkan pesanan (oleh admin)
     */
    public function cancel(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $order = Order::where('item_type', 'jasa')->findOrFail($id);

        if ($order->status === 'dibatalkan') {
            return redirect()->back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        if ($order->status === 'selesai') {
            return redirect()->back()->with('error', 'Pesanan yang sudah selesai tidak dapat dibatalkan.');
        }

        $order->update([
            'status'             => 'dibatalkan',
            'alasan_pembatalan'  => $request->alasan_pembatalan,
            'dibatalkan_oleh'    => 'admin',
            'cancelled_at'       => now(),
        ]);

        return redirect()->back()->with('success', 'Pesanan ' . ($order->order_number ?? '#' . $id) . ' berhasil dibatalkan.');
    }

    /**
     * Hapus satu pesanan
     */
    public function destroy($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::where('item_type', 'jasa')->findOrFail($id);

        // Hapus semua file dokumen dari storage
        $files = $order->getDokumenFiles();
        foreach ($files as $fileName) {
            $paths = [
                storage_path('app/private/dokumen_cetak/' . $fileName),
                storage_path('app/dokumen_cetak/' . $fileName),
            ];
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
        }

        $order->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }

    /**
     * Hapus massal — berdasarkan ID yang dicentang atau filter status + tanggal
     */
    public function destroyBulk(Request $request)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $mode = $request->get('bulk_mode', 'selected'); // 'selected' | 'filter'

        if ($mode === 'selected') {
            // Hapus berdasarkan checkbox yang dipilih
            $request->validate([
                'order_ids'   => 'required|array|min:1',
                'order_ids.*' => 'integer|exists:orders,id',
            ], [
                'order_ids.required' => 'Pilih minimal satu pesanan.',
            ]);

            $orders = Order::where('item_type', 'jasa')
                ->whereIn('id', $request->order_ids)
                ->get();

        } else {
            // Hapus berdasarkan filter status + umur pesanan
            $request->validate([
                'filter_status' => 'required|in:' . self::STATUS_MENUNGGU . ',diproses,selesai,dibatalkan,semua',
                'filter_older'  => 'required|in:7,14,30,60,90',
            ]);

            $query = Order::where('item_type', 'jasa')
                ->where('status', '!=', 'dibatalkan')
                ->where(function ($query) {
                    $query->whereIn('payment_status', ['dp_diterima', 'lunas', 'sisa_dibayar'])
                        ->orWhere(function ($sub) {
                            $sub->where('payment_status', 'menunggu_konfirmasi')
                                ->whereNotNull('bukti_bayar')
                                ->where('bukti_bayar', '!=', '');
                        });
                })
                ->where('created_at', '<', now()->subDays((int) $request->filter_older));

            if ($request->filter_status !== 'semua') {
                $query->where('status', $request->filter_status);
            }

            $orders = $query->get();

            if ($orders->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada pesanan yang sesuai filter untuk dihapus.');
            }
        }

        $deleted = 0;
        foreach ($orders as $order) {
            $files = $order->getDokumenFiles();
            foreach ($files as $fileName) {
                $paths = [
                    storage_path('app/private/dokumen_cetak/' . $fileName),
                    storage_path('app/dokumen_cetak/' . $fileName),
                ];
                foreach ($paths as $path) {
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
            $order->delete();
            $deleted++;
        }

        return redirect()->back()->with('success', "{$deleted} pesanan berhasil dihapus.");
    }

    /**
     * Download dokumen yang diunggah pelanggan.
     * Mendukung multi-file via query param ?fileIndex=0
     */
    public function download(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::findOrFail($id);
        $files = $order->getDokumenFiles();

        if (empty($files)) {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki dokumen.');
        }

        $fileIndex = (int) $request->get('fileIndex', 0);
        if (!isset($files[$fileIndex])) {
            $fileIndex = 0;
        }

        $fileName = $files[$fileIndex];

        $path = storage_path('app/private/dokumen_cetak/' . $fileName);
        if (!file_exists($path)) {
            $path = storage_path('app/dokumen_cetak/' . $fileName);
        }

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan di server.');
        }

        return response()->download($path, $fileName);
    }

    /**
     * Konfirmasi pembayaran (admin tandai lunas)
     */
    public function konfirmasiPembayaran(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $request->validate([
            'harga_final'         => 'nullable|integer|min:0',
            'catatan_pembayaran'  => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $mode = $request->input('payment_mode', 'dp');

        $catatanPembayaran = trim((string) ($request->input('catatan_pembayaran') ?: ''));
        $order->catatan_pembayaran = $catatanPembayaran !== ''
            ? $catatanPembayaran
            : ($mode === 'sisa' ? 'Pembayaran sisa telah diterima oleh admin.' : 'Pembayaran awal (DP) diterima oleh admin.');

        $finalPrice = $request->filled('harga_final') ? (int) $request->input('harga_final') : null;
        if ($finalPrice !== null && $finalPrice > 0) {
            $order->total_harga = $finalPrice;
        } elseif (($order->total_harga ?? 0) <= 0) {
            $order->total_harga = (int) ($order->estimasi_harga ?: 0);
        }

        if (Schema::hasColumn('orders', 'harga_final')) {
            $order->harga_final = $finalPrice !== null && $finalPrice > 0 ? $finalPrice : $order->total_harga;
        }

        if ($mode === 'sisa') {
            $order->payment_status = 'lunas';
            $order->paid_at = now();
        } else {
            $order->payment_status = 'dp_diterima';
            $order->paid_at = now();
            $order->status = 'diproses';
        }

        $order->save();

        $message = $mode === 'sisa'
            ? 'Pembayaran sisa pesanan ' . ($order->order_number ?? '#'.$id) . ' berhasil dikonfirmasi.'
            : 'Pembayaran awal (DP) pesanan ' . ($order->order_number ?? '#'.$id) . ' berhasil dikonfirmasi.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Setujui permintaan pembatalan dari pelanggan — batalkan pesanan cetak
     */
    public function setujuiPembatalan(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $order = Order::where('item_type', 'jasa')
            ->where('payment_status', self::PAYMENT_MINTA_BATAL)
            ->findOrFail($id);

        $order->update([
            'status'             => 'dibatalkan',
            'payment_status'     => 'dibatalkan',
            'alasan_pembatalan'  => $request->catatan ?: 'Pembatalan disetujui admin. Refund DP sedang diproses.',
            'dibatalkan_oleh'    => 'admin',
            'cancelled_at'       => now(),
            'catatan_pembayaran' => $request->catatan ?: 'Pembatalan disetujui. Refund DP sedang diproses via transfer.',
        ]);

        return redirect()->back()
            ->with('success', 'Pembatalan pesanan ' . ($order->order_number ?? '#'.$id) . ' disetujui.');
    }

    /**
     * Tolak permintaan pembatalan dari pelanggan — kembalikan ke status semula
     */
    public function tolakPembatalan(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $order = Order::where('item_type', 'jasa')
            ->where('payment_status', self::PAYMENT_MINTA_BATAL)
            ->findOrFail($id);

        $catatan = trim((string) ($request->catatan ?: ''));

        $order->update([
            'payment_status'             => 'menunggu_konfirmasi',
            'cancellation_reason'        => null,
            'cancellation_requested_at'  => null,
            'catatan_pembayaran'         => $catatan !== ''
                ? 'Permintaan pembatalan ditolak oleh admin: ' . $catatan
                : 'Permintaan pembatalan ditolak oleh admin.',
        ]);

        return redirect()->back()
            ->with('success', 'Permintaan pembatalan pesanan ' . ($order->order_number ?? '#'.$id) . ' ditolak. Status dikembalikan.');
    }

    /**
     * Tolak bukti pembayaran yang dinilai tidak valid.
     */
    public function tolakPembayaran(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $request->validate([
            'alasan_pembatalan' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $alasan = trim((string) ($request->input('alasan_pembatalan') ?: ''));

        // Bukti sisa pelunasan berbeda dari DP awal: DP sudah sah dan
        // pekerjaan telah selesai, sehingga pesanan tidak boleh kembali ke
        // antrean atau meminta DP lagi.
        $isRemainingPayment = $order->item_type === 'jasa'
            && $order->status === 'selesai'
            && in_array($order->payment_status, ['sisa_dibayar', 'menunggu_konfirmasi'], true);

        if ($isRemainingPayment) {
            $order->payment_status = 'dp_diterima';
            $order->catatan_pembayaran = $alasan !== ''
                ? 'Bukti sisa pelunasan ditolak oleh admin: ' . $alasan
                : 'Bukti sisa pelunasan ditolak oleh admin karena tidak sesuai/valid.';
        } else {
            $order->payment_status = 'ditolak';
            $order->catatan_pembayaran = $alasan !== ''
                ? 'Pembayaran ditolak oleh admin: ' . $alasan
                : 'Pembayaran ditolak oleh admin karena bukti tidak sesuai/valid.';
            $order->paid_at = null;
            $order->status = 'Menunggu Antrean';
        }

        $order->save();

        return redirect()->back()->with('success', $isRemainingPayment
            ? 'Bukti sisa pelunasan pesanan ' . ($order->order_number ?? '#'.$id) . ' ditolak. Pelanggan dapat mengirim ulang bukti sisa.'
            : 'Pembayaran pesanan ' . ($order->order_number ?? '#'.$id) . ' berhasil ditolak.');
    }

    /**
     * Download bukti bayar yang diunggah pelanggan
     */
    public function downloadBukti($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::findOrFail($id);

        if (!$order->bukti_bayar) {
            return redirect()->back()->with('error', 'Tidak ada bukti pembayaran untuk pesanan ini.');
        }

        $paths = [
            storage_path('app/private/bukti_bayar/' . $order->bukti_bayar),
            storage_path('app/public/bukti_bayar/' . $order->bukti_bayar),
        ];

        $path = null;
        foreach ($paths as $candidate) {
            if (file_exists($candidate)) {
                $path = $candidate;
                break;
            }
        }

        if (!$path) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        $mimeType = mime_content_type($path);
        if (str_starts_with($mimeType, 'image/')) {
            return response()->file($path, ['Content-Type' => $mimeType]);
        }

        return response()->download($path, $order->bukti_bayar);
    }
}
