<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; // PERLU DITAMBAHKAN

class HomeController extends Controller
{
    /**
     * Halaman Utama (Home)
     * Menampilkan produk terbaru secara terbatas (untuk Slider) dan data Keranjang
     */
    public function index()
    {
        // PERBAIKAN: Mengambil produk terbaru terbatas (8 produk) agar hemat ruang di Home
        $products = Product::with('category')->latest()->take(8)->get();

        // LOGIKA ASLI KERANJANG KAMU (Tetap dipertahankan agar tidak rusak)
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

        // Mengirim data produk terbatas dan keranjang ke halaman depan
        return view('home', compact('products', 'cartItems', 'cartTotal', 'cartCount'));
    }

    /**
     * FUNGSI BARU: Halaman Khusus Katalog Produk ATK (Langkah A)
     */
    public function katalog(Request $request)
    {
        // 1. Ambil semua kategori untuk tombol filter di atas
        $categories = Category::orderBy('name')->get();

        // 2. Cek apakah pembeli sedang menyaring kategori tertentu
        $selectedCategory = $request->get('category');

        // 3. Ambil kata kunci pencarian produk
        $search = $request->get('search');

        // 4. Ambil data produk dengan sistem Pagination (12 produk per halaman)
        $products = Product::with('category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->whereHas('category', function ($q) use ($selectedCategory) {
                    $q->where('name', $selectedCategory);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name_produk', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'LIKE', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(12);

        // LOGIKA KERANJANG (Diperlukan juga di halaman katalog agar tombol beli berfungsi)
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

        return view('katalog', compact('products', 'categories', 'selectedCategory', 'search', 'cartItems', 'cartTotal', 'cartCount'));
    }
}