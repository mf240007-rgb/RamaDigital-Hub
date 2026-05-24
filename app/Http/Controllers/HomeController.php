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

        // Mengirim data produk ke halaman depan (home.blade.php)
        return view('home', compact('products')); 
    }
}
