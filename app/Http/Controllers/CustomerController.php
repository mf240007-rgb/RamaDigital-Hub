<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    private function guardAdmin()
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin terlebih dahulu.');
        }

        return null;
    }

    public function index()
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $customers = User::whereIn('role', ['pelanggan', 'customer'])
            ->orderBy('full_name')
            ->get();

        return view('admin.customers.index', compact('customers'));
    }

    public function history($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $customer = User::find($id);

        if (! $customer || ! in_array($customer->role, ['pelanggan', 'customer'])) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $orders = Order::where('user_id', $customer->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.customers.history', compact('customer', 'orders'));
    }

    public function pesananSaya()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login untuk melihat pesanan Anda.');
        }

        $tipe = request('tipe', 'semua'); // semua | cetak | atk

        $query = Order::with('user')
            ->where('user_id', Auth::id());

        if ($tipe === 'cetak') {
            $query->where('item_type', 'jasa');
        } elseif ($tipe === 'atk') {
            $query->where('item_type', 'produk');
        }

        $orders = $query->orderByDesc('created_at')->get();

        // Untuk badge tab — selalu hitung semua tipe
        $allOrders = Order::where('user_id', Auth::id())->get();

        // Kirim cartCount agar badge keranjang di navbar muncul
        $cartKey   = 'cart_user_' . Auth::id();
        $cartCount = count(session($cartKey, []));

        return view('customer.orders', compact('orders', 'allOrders', 'cartCount', 'tipe'));
    }

    public function nota($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login untuk melihat nota pesanan Anda.');
        }

        $order = Order::with('user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('customer.nota', compact('order'));
    }

    public function uploadBuktiPembayaran(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login untuk mengunggah bukti pembayaran.');
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format gambar harus JPG atau PNG.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('item_type', ['produk', 'jasa'])
            ->firstOrFail();

        $file = $request->file('bukti_bayar');
        $fileName = time() . '_' . Auth::id() . '_bukti.' . $file->getClientOriginalExtension();
        $file->storeAs('bukti_bayar', $fileName, 'public');

        if ($order->bukti_bayar) {
            $oldPath = $order->buktiBayarPath();
            if ($oldPath && file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $order->bukti_bayar = $fileName;
        $order->payment_status = $order->payment_status === 'dp_diterima' ? 'sisa_dibayar' : 'menunggu_konfirmasi';
        $order->catatan_pembayaran = null;
        $order->paid_at = null;
        $order->save();

        return redirect()->route('customer.orders')
            ->with('success', 'Bukti pembayaran berhasil diunggah dan menunggu verifikasi admin.');
    }

    public function lihatBuktiPembayaran($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login untuk melihat bukti pembayaran.');
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('item_type', ['produk', 'jasa'])
            ->firstOrFail();

        if (! $order->bukti_bayar) {
            return redirect()->back()->with('error', 'Bukti pembayaran belum tersedia.');
        }

        $path = $order->buktiBayarPath();

        if (! $path || ! file_exists($path)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }

        return response()->file($path);
    }

    public function ajukanPembatalan(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ], [
            'cancellation_reason.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('item_type', ['produk', 'jasa'])
            ->firstOrFail();

        $canCancelImmediately = $order->item_type === 'jasa'
            && $order->status === 'Menunggu Antrean'
            && $order->payment_status !== 'lunas';

        $canRequestCancellation = $order->item_type === 'produk'
            && $order->payment_status === 'menunggu_konfirmasi';

        if (!($canCancelImmediately || $canRequestCancellation)) {
            return redirect()->route('customer.orders')
                ->with('error', 'Pembatalan hanya bisa diajukan saat pesanan masih menunggu pembayaran DP atau verifikasi.');
        }

        if ($order->status === 'dibatalkan') {
            return redirect()->route('customer.orders')
                ->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        if ($order->cancellation_requested_at && $order->item_type === 'produk') {
            return redirect()->route('customer.orders')
                ->with('error', 'Permintaan pembatalan sudah pernah diajukan dan sedang diproses admin.');
        }

        if ($order->item_type === 'jasa') {
            // Jasa cetak: hanya boleh ajukan batal saat Menunggu Antrean dan DP belum diterima
            if ($order->status !== 'Menunggu Antrean' || $order->payment_status === 'dp_diterima') {
                return redirect()->route('customer.orders')
                    ->with('error', 'Pembatalan pesanan cetak hanya bisa diajukan saat masih menunggu konfirmasi DP.');
            }

            // Cegah duplikat permintaan
            if ($order->cancellation_requested_at) {
                return redirect()->route('customer.orders')
                    ->with('error', 'Permintaan pembatalan sudah pernah diajukan dan sedang diproses admin.');
            }

            // Sama seperti ATK — masuk ke menunggu_persetujuan_batal, bukan langsung dibatalkan
            $order->update([
                'payment_status'             => 'menunggu_persetujuan_batal',
                'cancellation_reason'        => $request->cancellation_reason,
                'cancellation_requested_at'  => now(),
                'catatan_pembayaran'         => null,
            ]);

            return redirect()->route('customer.orders')
                ->with('success', 'Permintaan pembatalan pesanan cetak berhasil diajukan. Admin akan menghubungi kamu via WhatsApp untuk proses refund DP.');
        }

        $order->update([
            'payment_status'             => 'menunggu_persetujuan_batal',
            'cancellation_reason'        => $request->cancellation_reason,
            'cancellation_requested_at'  => now(),
            'catatan_pembayaran'         => null,
        ]);

        return redirect()->route('customer.orders')
            ->with('success', 'Permintaan pembatalan berhasil diajukan. Admin akan menghubungi kamu via WhatsApp untuk proses refund.');
    }

    public function resetPassword(Request $request, $id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $customer = User::find($id);

        if (! $customer || ! in_array($customer->role, ['pelanggan', 'customer'])) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Pelanggan tidak ditemukan.');
        }

        $customer->password = bcrypt('password123');
        $customer->save();

        return redirect()->route('admin.customers.index')
            ->with('success', "Password pelanggan '{$customer->full_name}' berhasil di-reset ke default.");
    }

    /**
     * Download dokumen yang diunggah pelanggan
     */
    public function downloadDokumen($orderId)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $order = Order::find($orderId);

        if (! $order || ! $order->file_dokumen) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $path = storage_path('app/private/dokumen_cetak/' . $order->file_dokumen);

        if (!file_exists($path)) {
            $path = storage_path('app/dokumen_cetak/' . $order->file_dokumen);
        }

        if (! file_exists($path)) {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan di server.');
        }

        return response()->download($path, $order->file_dokumen);
    }
}
