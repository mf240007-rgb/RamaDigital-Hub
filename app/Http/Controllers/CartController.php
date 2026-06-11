<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function view()
    {
        $cart = session('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->harga * $quantity,
                ];
                $total += $product->harga * $quantity;
            }
        }

        return view('cart.index', [
            'products' => $products,
            'total' => $total,
            'cartCount' => count($cart),
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $product = Product::find($validated['product_id']);

        if (!$product || $product->stok <= 0) {
            return back()->with('error', 'Produk tidak tersedia atau stok habis.');
        }

        $cart = session('cart', []);
        $quantity = isset($cart[$product->id]) ? $cart[$product->id] + 1 : 1;

        if ($quantity > $product->stok) {
            return back()->with('error', 'Jumlah produk melebihi stok yang tersedia.');
        }

        $cart[$product->id] = $quantity;
        session(['cart' => $cart]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validated['product_id']);

        if (!$product || $product->stok <= 0) {
            return back()->with('error', 'Produk tidak tersedia atau stok habis.');
        }

        if ($validated['quantity'] > $product->stok) {
            return back()->with('error', 'Jumlah produk melebihi stok yang tersedia.');
        }

        $cart = session('cart', []);
        $cart[$product->id] = $validated['quantity'];
        session(['cart' => $cart]);

        return back()->with('success', 'Kuantitas keranjang berhasil diperbarui.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$validated['product_id']])) {
            unset($cart[$validated['product_id']]);
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
