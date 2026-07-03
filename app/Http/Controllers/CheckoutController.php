<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /** Halaman checkout keranjang */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login untuk melanjutkan checkout.');
        }

        $cartKey = 'cart_user_' . Auth::id();
        $cart    = session($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('cart.view')
                ->with('error', 'Keranjang kosong.');
        }

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal = $product->harga * $quantity;
                $items[]  = compact('product', 'quantity', 'subtotal');
                $total   += $subtotal;
            }
        }

        return view('checkout.index', compact('items', 'total'));
    }

    /** Proses checkout: simpan order + bukti bayar */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'bukti_bayar' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format gambar harus JPG atau PNG.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $cartKey = 'cart_user_' . Auth::id();
        $cart    = session($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Keranjang kosong.');
        }

        // Hitung total & buat detail
        $total   = 0;
        $details = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $total   += $product->harga * $quantity;
                $details[] = $product->name_produk . ' x' . $quantity;
            }
        }

        // Simpan bukti pembayaran jika sudah diupload saat checkout
        $fileName = null;
        if ($request->hasFile('bukti_bayar')) {
            $file     = $request->file('bukti_bayar');
            $fileName = time() . '_' . Auth::id() . '_bukti.' . $file->getClientOriginalExtension();
            $file->storeAs('bukti_bayar', $fileName, 'public');
        }

        $paymentStatus = $fileName ? 'menunggu_konfirmasi' : 'ditolak';

        // Buat order
        Order::create([
            'order_number'   => Order::generateOrderNumber(),
            'user_id'        => Auth::id(),
            'item_type'      => 'produk',
            'detail_pesanan' => implode(', ', $details),
            'total_harga'    => $total,
            'status'         => 'Menunggu Antrean',
            'payment_status' => $paymentStatus,
            'bukti_bayar'    => $fileName,
        ]);

        // Kosongkan keranjang
        session()->forget($cartKey);

        return redirect()->route('customer.orders')
            ->with('success', $fileName
                ? 'Pesanan berhasil dikirim! Admin akan memverifikasi bukti pembayaran kamu dan menghubungi via WhatsApp.'
                : 'Pesanan berhasil disimpan sebagai ditolak sementara. Kamu bisa upload bukti pembayaran dari halaman Pesanan Saya.');
    }
}
