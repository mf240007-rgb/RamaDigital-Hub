<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /** Halaman checkout keranjang (tampilkan ringkasan + form QRIS) */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login untuk melanjutkan checkout.');
        }

        $cartKey  = 'cart_user_' . Auth::id();
        $cart     = session($cartKey, []);

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
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diupload.',
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format gambar harus JPG atau PNG.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $cartKey = 'cart_user_' . Auth::id();
        $cart    = session($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Keranjang kosong.');
        }

        // Hitung total & detail
        $items  = [];
        $total  = 0;
        $details = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal  = $product->harga * $quantity;
                $items[]   = compact('product', 'quantity', 'subtotal');
                $total    += $subtotal;
                $details[] = $product->name_produk . ' x' . $quantity;
            }
        }

        // Upload bukti bayar
        $file     = $request->file('bukti_bayar');
        $fileName = time() . '_' . Auth::id() . '_bukti.' . $file->getClientOriginalExtension();
        $file->storeAs('bukti_bayar', $fileName, 'local');

        // Buat order
        Order::create([
            'order_number'   => Order::generateOrderNumber(),
            'user_id'        => Auth::id(),
            'item_type'      => 'produk',
            'detail_pesanan' => implode(', ', $details),
            'total_harga'    => $total,
            'status'         => 'Menunggu Antrean',
            'payment_status' => 'menunggu_konfirmasi',
            'bukti_bayar'    => $fileName,
        ]);

        // Kosongkan keranjang
        session()->forget($cartKey);

        return redirect()->route('home')
            ->with('success', 'Pesanan berhasil dibuat! Admin akan memverifikasi pembayaran kamu dan menghubungi via WhatsApp.');
    }
}
