<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil semua produk ATK dari database
        $products = Product::all();

        // Bangun data keranjang dari session
        $cart = session('cart', []);
        $cartCount = array_sum($cart);
        $cartItems = [];
        $cartTotal = 0;

        if (!empty($cart)) {
            $cartProducts = Product::whereIn('id', array_keys($cart))->get();

            foreach ($cartProducts as $product) {
                $quantity = $cart[$product->id] ?? 0;
                $subtotal = $product->harga * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $cartTotal += $subtotal;
            }
        }

        // Mengirim data produk dan keranjang ke halaman depan
        return view('home', compact('products', 'cartItems', 'cartTotal', 'cartCount'));
    }
}
