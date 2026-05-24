<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| RamaDigital Hub — Web Routes
|--------------------------------------------------------------------------
| Di sinilah semua "alamat URL" website kita didaftarkan.
| Setiap route menghubungkan URL dengan Controller atau View yang sesuai.
*/

// -----------------------------------------------------------------------
// ROUTE HALAMAN UTAMA (Pelanggan Umum)
// Akses: http://localhost:8000/
// -----------------------------------------------------------------------
Route::get('/', function () {
    return view('home'); // Memanggil file: resources/views/home.blade.php
});


// -----------------------------------------------------------------------
// ROUTE GRUP ADMIN
// Semua route admin diawali dengan prefix '/admin'
// -----------------------------------------------------------------------

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