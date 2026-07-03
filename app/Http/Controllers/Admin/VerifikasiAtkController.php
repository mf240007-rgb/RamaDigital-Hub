<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VerifikasiAtkController extends Controller
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
     * Halaman daftar pesanan ATK yang menunggu konfirmasi pembayaran
     */
    public function index(Request $request)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $filter  = $request->get('filter', 'menunggu_konfirmasi'); // default: menunggu
        if ($filter === 'belum_bayar') {
            $filter = 'ditolak';
        }
        $keyword = $request->get('search');

        $query = Order::with('user')
            ->where('item_type', 'produk')
            ->when($keyword, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('full_name', 'LIKE', '%'.$keyword.'%')
            ))
            ->when($filter !== 'semua', fn($q) => $q->where('payment_status', $filter))
            ->orderByDesc('created_at');

        $orders = $query->paginate(15);

        // Hitung badge tiap status
        $counts = [
            'menunggu_konfirmasi' => Order::where('item_type','produk')->where('payment_status','menunggu_konfirmasi')->count(),
            'lunas'               => Order::where('item_type','produk')->where('payment_status','lunas')->count(),
            'ditolak'             => Order::where('item_type','produk')->where('payment_status','ditolak')->count(),
            'semua'               => Order::where('item_type','produk')->count(),
        ];

        return view('admin.verifikasi-atk.index', compact('orders', 'filter', 'keyword', 'counts'));
    }

    /**
     * Tampilkan / download bukti bayar
     */
    public function lihatBukti($id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $order = Order::where('item_type', 'produk')->findOrFail($id);

        if (!$order->bukti_bayar) {
            return redirect()->back()->with('error', 'Tidak ada bukti pembayaran untuk pesanan ini.');
        }

        $path = $order->buktiBayarPath();

        if (! $path || !file_exists($path)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan di server.');
        }

        // Deteksi tipe file
        $mime = mime_content_type($path);
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /**
     * Download bukti bayar
     */
    public function downloadBukti($id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $order = Order::where('item_type', 'produk')->findOrFail($id);

        if (!$order->bukti_bayar) {
            return redirect()->back()->with('error', 'Tidak ada bukti pembayaran.');
        }

        $path = $order->buktiBayarPath();

        if (! $path || !file_exists($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($path, $order->bukti_bayar);
    }

    /**
     * Konfirmasi lunas atau tolak pembayaran
     */
    public function konfirmasi(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $request->validate([
            'aksi'    => 'required|in:lunas,tolak',
            'catatan' => 'nullable|string|max:300',
        ]);

        $order = Order::where('item_type', 'produk')->findOrFail($id);

        if ($request->aksi === 'lunas') {
            $order->payment_status      = 'lunas';
            $order->catatan_pembayaran  = $request->catatan ?: 'Pembayaran dikonfirmasi oleh admin.';
            $order->save();

            return redirect()->back()
                ->with('success', 'Pembayaran pesanan '
                    .($order->order_number ?? '#'.$id)
                    .' berhasil dikonfirmasi lunas.');
        }

        // Tolak
        $order->payment_status     = 'ditolak';
        $order->catatan_pembayaran = $request->catatan ?: 'Bukti pembayaran tidak valid.';
        $order->save();

        return redirect()->back()
            ->with('error', 'Pembayaran pesanan '
                .($order->order_number ?? '#'.$id)
                .' ditolak.');
    }
}
