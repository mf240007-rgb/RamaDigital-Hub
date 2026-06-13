<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController;


// ROUTE HALAMAN UTAMA (Pelanggan Umum)
Route::get('/', [HomeController::class, 'index'])->name('home');

// ROUTE GRUP USER (Pelanggan)
// Halaman Login User
Route::get('/login', [UserController::class, 'showLoginForm'])->name('user.login');
Route::post('/login', [UserController::class, 'login'])->name('user.login.submit');

//Halaman Produk
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog.index');

// Halaman Register User
Route::get('/register', [UserController::class, 'showRegisterForm'])->name('user.register');
Route::post('/register', [UserController::class, 'register'])->name('user.register.submit');

// Lupa kata sandi / reset password pelanggan
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::get('/reset-password', [AuthController::class, 'showChangePasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

// Logout User (menerima POST dan GET untuk kompatibilitas tombol/tautan)
Route::match(['get', 'post'], '/logout', [UserController::class, 'logout'])->name('user.logout');

// ROUTE GRUP ADMIN
// Semua route admin diawali dengan prefix '/admin'
// Halaman Login Admin (GET = tampilkan form)
// Akses: http://localhost:8000/admin/login
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])
    ->name('admin.login');

// Proses Login Admin (POST = kirim data form)
// URL sama, tapi method POST untuk menerima data dari form
Route::post('/admin/login', [AdminController::class, 'login'])
    ->name('admin.login.submit');

// Halaman Dashboard Admin (setelah login berhasil)
// Akses: http://localhost:8000/admin/dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard');

// Proses Logout Admin
Route::get('/admin/logout', [AdminController::class, 'logout'])
    ->name('admin.logout');

// Rute untuk mengelola ATK di sisi Admin
Route::get('/admin/produk', [ProductController::class, 'index'])->name('admin.produk.index');
Route::get('/admin/produk/create', [ProductController::class, 'create'])->name('admin.produk.create');
Route::post('/admin/produk/store', [ProductController::class, 'store'])->name('admin.produk.store');
Route::post('/admin/kategori/store', [ProductController::class, 'storeCategory'])->name('admin.kategori.store');
Route::delete('/admin/kategori/{id}', [ProductController::class, 'destroyCategory'])->name('admin.kategori.destroy');
Route::delete('/admin/produk/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.produk.destroy');
Route::get('/admin/produk/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.produk.edit');
Route::put('/admin/produk/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.produk.update');

// Rute Keranjang Belanja
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Admin panel - Data Pelanggan
Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
Route::get('/admin/customers/{id}/history', [CustomerController::class, 'history'])->name('admin.customers.history');
Route::post('/admin/customers/{id}/reset-password', [CustomerController::class, 'resetPassword'])->name('admin.customers.reset');
