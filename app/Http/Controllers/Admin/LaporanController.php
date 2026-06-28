<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function guardAdmin()
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin.');
        }
        return null;
    }

    public function index(Request $request)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $bulan = $request->get('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);

        // ── Pesanan ATK (produk) bulan ini ──────────────────────────
        $query = Order::with('user')
            ->where('item_type', 'produk')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bln);

        // Filter khusus menunggu verifikasi (dari sidebar shortcut)
        $filterVerif = $request->get('filter');
        if ($filterVerif === 'menunggu') {
            $query->where('payment_status', 'menunggu_konfirmasi');
        }

        $atkOrders = $query->orderByDesc('created_at')->get();

        // ── Ringkasan ATK ────────────────────────────────────────────
        $totalPesananAtk    = $atkOrders->count();
        $pesananSelesaiAtk  = $atkOrders->where('status', 'selesai')->count();
        $pesananProsesAtk   = $atkOrders->whereIn('status', ['Menunggu Antrean', 'diproses'])->count();
        $pesananBatalAtk    = $atkOrders->where('status', 'dibatalkan')->count();

        // Pendapatan = total_harga dari pesanan ATK yang status Selesai
        $pendapatanAtk = $atkOrders->where('status', 'selesai')->sum('total_harga');

        // ── Rekap pendapatan ATK per bulan (12 bulan terakhir) ───────
        $rekapBulanan = collect();
        for ($i = 11; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $income = Order::where('item_type', 'produk')
                ->where('status', 'selesai')
                ->whereYear('created_at', $dt->year)
                ->whereMonth('created_at', $dt->month)
                ->sum('total_harga');
            $rekapBulanan->push([
                'label'  => $dt->isoFormat('MMM YY'),
                'income' => $income,
            ]);
        }

        // ── Produk terlaris berdasarkan kemunculan nama di detail_pesanan ──
        // Ambil semua nama produk dari DB lalu hitung frekuensi di detail_pesanan
        $semuaProduk   = Product::orderBy('name_produk')->pluck('name_produk');
        $produkTerlaris = [];
        foreach ($semuaProduk as $namaProduk) {
            $jumlah = Order::where('item_type', 'produk')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bln)
                ->where('detail_pesanan', 'LIKE', '%' . $namaProduk . '%')
                ->count();
            if ($jumlah > 0) {
                $produkTerlaris[$namaProduk] = $jumlah;
            }
        }
        arsort($produkTerlaris);
        $produkTerlaris = array_slice($produkTerlaris, 0, 5, true); // top 5

        // ── Daftar bulan untuk filter ───────────────────────────────
        $bulanList = [];
        for ($i = 0; $i < 12; $i++) {
            $dt = now()->subMonths($i);
            $bulanList[$dt->format('Y-m')] = $dt->isoFormat('MMMM Y');
        }

        return view('admin.laporan.index', compact(
            'bulan', 'bulanList',
            'atkOrders',
            'totalPesananAtk', 'pesananSelesaiAtk', 'pesananProsesAtk', 'pesananBatalAtk',
            'pendapatanAtk',
            'rekapBulanan',
            'produkTerlaris'
        ));
    }

    /**
     * Lihat bukti pembayaran (download/view)
     */
    public function lihatBukti($id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $order = Order::where('item_type', 'produk')->findOrFail($id);

        if (!$order->bukti_bayar) {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki bukti pembayaran.');
        }

        $path = storage_path('app/public/bukti_bayar/' . $order->bukti_bayar);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        return response()->file($path);
    }

    /**
     * Konfirmasi / tolak pembayaran ATK
     */
    public function verifikasiPembayaran(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $request->validate([
            'aksi'               => 'required|in:lunas,ditolak',
            'catatan_verifikasi' => 'nullable|string|max:300',
        ]);

        $order = Order::where('item_type', 'produk')->findOrFail($id);

        if ($request->aksi === 'lunas') {
            $order->update([
                'payment_status'     => 'lunas',
                'status'             => 'selesai',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'paid_at'            => now(),
            ]);
            $msg = 'Pembayaran pesanan ' . ($order->order_number ?? '#'.$id) . ' berhasil dikonfirmasi lunas.';
        } else {
            $order->update([
                'payment_status'     => 'ditolak',
                'catatan_verifikasi' => $request->catatan_verifikasi ?: 'Bukti pembayaran tidak valid.',
            ]);
            $msg = 'Pembayaran pesanan ' . ($order->order_number ?? '#'.$id) . ' ditolak.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
