<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    /** Proses checkout: validasi stok → simpan order → kurangi stok → upload bukti */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'bukti_bayar.required' => 'Bukti pembayaran wajib diupload sebelum mengirim pesanan.',
            'bukti_bayar.image'    => 'File harus berupa gambar.',
            'bukti_bayar.mimes'    => 'Format gambar harus JPG atau PNG.',
            'bukti_bayar.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $cartKey = 'cart_user_' . Auth::id();
        $cart    = session($cartKey, []);

        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Keranjang kosong.');
        }

        // ── Validasi stok sebelum proses ──────────────────────
        $stockErrors = [];
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product) continue;
            if ($product->stok < $quantity) {
                $stockErrors[] = "Stok <strong>{$product->name_produk}</strong> tidak cukup. "
                    . "Tersedia: {$product->stok} pcs, diminta: {$quantity} pcs.";
            }
        }

        if (!empty($stockErrors)) {
            return redirect()->route('cart.view')
                ->with('error', 'Pesanan gagal: ' . implode(' ', $stockErrors));
        }

        // ── Proses dalam transaction ────────────────────────────
        try {
            DB::beginTransaction();

            $total   = 0;
            $details = [];

            foreach ($cart as $productId => $quantity) {
                // lockForUpdate mencegah race condition jika 2 user beli produk sama bersamaan
                $product = Product::lockForUpdate()->find($productId);
                if (!$product) continue;

                // Double check stok di dalam transaction
                if ($product->stok < $quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.view')
                        ->with('error', "Stok {$product->name_produk} habis saat pemrosesan. Silakan cek kembali keranjang.");
                }

                $total   += $product->harga * $quantity;
                $details[] = $product->name_produk . ' x' . $quantity;

                // Kurangi stok sejumlah yang dibeli
                $product->decrement('stok', $quantity);
            }

            // Simpan bukti pembayaran
            $fileName = null;
            if ($request->hasFile('bukti_bayar')) {
                $file     = $request->file('bukti_bayar');
                $fileName = time() . '_' . Auth::id() . '_bukti.' . $file->getClientOriginalExtension();
                $file->storeAs('bukti_bayar', $fileName, 'public');
            }

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

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.view')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }

        // Kosongkan keranjang
        session()->forget($cartKey);

        return redirect()->route('customer.orders')
            ->with('success', 'Pesanan berhasil dikirim! Admin akan memverifikasi bukti pembayaran kamu dan menghubungi via WhatsApp.');
    }
}
