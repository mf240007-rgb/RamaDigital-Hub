<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PrintOrderController extends Controller
{
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

        $keyword = $request->get('search');
        $status  = $request->get('status');

        $orders = Order::with('user')
            ->where('item_type', 'jasa')
            ->when($keyword, function ($q) use ($keyword) {
                $q->whereHas('user', function ($uq) use ($keyword) {
                    $uq->where('full_name', 'LIKE', '%' . $keyword . '%');
                })->orWhere('detail_pesanan', 'LIKE', '%' . $keyword . '%');
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.print-orders.index', compact('orders', 'keyword', 'status'));
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
            'status' => 'required|in:Menunggu Antrean,diproses,selesai',
        ]);

        $order = Order::where('item_type', 'jasa')->findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
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

        // Hapus file dokumen dari storage jika ada
        if ($order->file_dokumen) {
            $paths = [
                storage_path('app/private/dokumen_cetak/' . $order->file_dokumen),
                storage_path('app/dokumen_cetak/' . $order->file_dokumen),
            ];
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    @unlink($path);
                    break;
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
                'filter_status' => 'required|in:Menunggu Antrean,diproses,selesai,dibatalkan,semua',
                'filter_older'  => 'required|in:7,14,30,60,90',
            ]);

            $query = Order::where('item_type', 'jasa')
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
            if ($order->file_dokumen) {
                $paths = [
                    storage_path('app/private/dokumen_cetak/' . $order->file_dokumen),
                    storage_path('app/dokumen_cetak/' . $order->file_dokumen),
                ];
                foreach ($paths as $path) {
                    if (file_exists($path)) {
                        @unlink($path);
                        break;
                    }
                }
            }
            $order->delete();
            $deleted++;
        }

        return redirect()->back()->with('success', "{$deleted} pesanan berhasil dihapus.");
    }

    /**
     * Download dokumen yang diunggah pelanggan
     */
    public function download($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::findOrFail($id);

        if (!$order->file_dokumen) {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki dokumen.');
        }

        $path = storage_path('app/private/dokumen_cetak/' . $order->file_dokumen);
        if (!file_exists($path)) {
            $path = storage_path('app/dokumen_cetak/' . $order->file_dokumen);
        }

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan di server.');
        }

        return response()->download($path, $order->file_dokumen);
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
        $order->payment_status       = 'lunas';
        $order->harga_final          = $request->harga_final ?: $order->total_harga;
        $order->catatan_pembayaran   = $request->catatan_pembayaran;
        // Jika harga final > 0, update total_harga juga
        if ($request->harga_final) {
            $order->total_harga = $request->harga_final;
        }
        $order->save();

        return redirect()->back()->with('success', 'Pembayaran pesanan ' . ($order->order_number ?? '#'.$id) . ' berhasil dikonfirmasi.');
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

        $path = storage_path('app/private/bukti_bayar/' . $order->bukti_bayar);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        return response()->download($path, $order->bukti_bayar);
    }
}
