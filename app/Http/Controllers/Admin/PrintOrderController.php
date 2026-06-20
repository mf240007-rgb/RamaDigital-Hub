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
     * Download dokumen yang diunggah pelanggan
     */
    public function download($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::where('item_type', 'jasa')->findOrFail($id);

        if (!$order->file_dokumen) {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki dokumen.');
        }

        // Laravel 11 disk 'local' menyimpan ke storage/app/private/
        $path = storage_path('app/private/dokumen_cetak/' . $order->file_dokumen);

        // Fallback ke path lama jika ada file di sana
        if (!file_exists($path)) {
            $path = storage_path('app/dokumen_cetak/' . $order->file_dokumen);
        }

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan di server.');
        }

        return response()->download($path, $order->file_dokumen);
    }
}
