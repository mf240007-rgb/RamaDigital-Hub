<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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

        $tipe  = $request->get('tipe', 'semua');   // semua | atk | cetak
        $bulan = $request->get('bulan', now()->format('Y-m'));

        [$tahun, $bln] = explode('-', $bulan . '-01');

        // Query dasar
        $query = Order::with('user')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bln);

        if ($tipe === 'atk') {
            $query->where('item_type', 'produk');
        } elseif ($tipe === 'cetak') {
            $query->where('item_type', 'jasa');
        }

        $orders = $query->orderByDesc('created_at')->get();

        // Ringkasan
        $totalPesanan   = $orders->count();
        $totalPendapatan = $orders->where('payment_status', 'lunas')->sum('total_harga');
        $pesananSelesai  = $orders->where('status', 'selesai')->count();
        $pesananProses   = $orders->whereIn('status', ['Menunggu Antrean', 'diproses'])->count();

        // Breakdown per tipe
        $jumlahAtk    = $orders->where('item_type', 'produk')->count();
        $jumlahCetak  = $orders->where('item_type', 'jasa')->count();

        // Breakdown pembayaran
        $lunas           = $orders->where('payment_status', 'lunas')->count();
        $menungguKonfirmasi = $orders->where('payment_status', 'menunggu_konfirmasi')->count();
        $belumBayar      = $orders->where('payment_status', 'belum_bayar')->count();

        // Daftar bulan untuk filter (12 bulan terakhir)
        $bulanList = [];
        for ($i = 0; $i < 12; $i++) {
            $dt = now()->subMonths($i);
            $bulanList[$dt->format('Y-m')] = $dt->isoFormat('MMMM Y');
        }

        return view('admin.laporan.index', compact(
            'orders', 'tipe', 'bulan', 'bulanList',
            'totalPesanan', 'totalPendapatan', 'pesananSelesai', 'pesananProses',
            'jumlahAtk', 'jumlahCetak',
            'lunas', 'menungguKonfirmasi', 'belumBayar'
        ));
    }
}
