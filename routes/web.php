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
// Semua route admin memakai prefix rahasia dari ADMIN_PATH di file .env.
// Halaman Login Admin (GET = tampilkan form)
// Akses: http://localhost:8000/{ADMIN_PATH}/login
Route::get('/' . config('admin.path') . '/login', [AdminController::class, 'showLoginForm'])
    ->name('admin.login');

// Proses Login Admin (POST = kirim data form)
// URL sama, tapi method POST untuk menerima data dari form
Route::post('/' . config('admin.path') . '/login', [AdminController::class, 'login'])
    ->name('admin.login.submit');

// Halaman Dashboard Admin (setelah login berhasil)
// Akses: http://localhost:8000/{ADMIN_PATH}/dashboard
Route::get('/' . config('admin.path') . '/dashboard', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard');

// Proses Logout Admin
Route::get('/' . config('admin.path') . '/logout', [AdminController::class, 'logout'])
    ->name('admin.logout');

// Halaman Pengaturan Admin
Route::get('/' . config('admin.path') . '/settings', [AdminController::class, 'showSettings'])->name('admin.settings');
Route::post('/' . config('admin.path') . '/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
Route::post('/' . config('admin.path') . '/settings/qris', [AdminController::class, 'updateSettings'])->name('admin.settings.qris');

// Rute untuk mengelola ATK di sisi Admin
Route::get('/' . config('admin.path') . '/produk', [ProductController::class, 'index'])->name('admin.produk.index');
Route::get('/' . config('admin.path') . '/produk/create', [ProductController::class, 'create'])->name('admin.produk.create');
Route::post('/' . config('admin.path') . '/produk/store', [ProductController::class, 'store'])->name('admin.produk.store');
Route::post('/' . config('admin.path') . '/kategori/store', [ProductController::class, 'storeCategory'])->name('admin.kategori.store');
Route::delete('/' . config('admin.path') . '/kategori/{id}', [ProductController::class, 'destroyCategory'])->name('admin.kategori.destroy');
Route::delete('/' . config('admin.path') . '/produk/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.produk.destroy');
Route::get('/' . config('admin.path') . '/produk/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.produk.edit');
Route::put('/' . config('admin.path') . '/produk/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.produk.update');

// Rute Keranjang Belanja
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Admin panel - Data Pelanggan
Route::get('/' . config('admin.path') . '/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
Route::get('/' . config('admin.path') . '/customers/{id}/history', [CustomerController::class, 'history'])->name('admin.customers.history');
Route::post('/' . config('admin.path') . '/customers/{id}/reset-password', [CustomerController::class, 'resetPassword'])->name('admin.customers.reset');

// Route untuk menghapus pelanggan
Route::delete('/' . config('admin.path') . '/customers/{id}', [AdminController::class, 'destroyCustomer'])->name('admin.customers.destroy');

// Route submit jasa cetak (user)
Route::post('/jasa-cetak', [HomeController::class, 'submitCetak'])->name('cetak.submit');

// Route cek status pesanan (publik, tanpa login)
Route::post('/cek-status', [HomeController::class, 'cekStatus'])->name('cetak.cek-status');

// Route batalkan pesanan oleh pelanggan (publik, via nomor pesanan)
Route::post('/batalkan-pesanan', [HomeController::class, 'cancelOrder'])->name('cetak.cancel');

// Route download dokumen (admin) — dari halaman riwayat pelanggan
Route::get('/' . config('admin.path') . '/orders/{orderId}/download', [CustomerController::class, 'downloadDokumen'])->name('admin.orders.download');

// Checkout ATK (user)
Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

// Pesanan ATK pelanggan
Route::get('/pesanan-saya', [CustomerController::class, 'pesananSaya'])->name('customer.orders');
Route::get('/pesanan-saya/{id}/nota', [CustomerController::class, 'nota'])->name('customer.orders.nota');
Route::post('/pesanan-saya/{id}/bukti', [CustomerController::class, 'uploadBuktiPembayaran'])->name('customer.orders.upload-bukti');
Route::get('/pesanan-saya/{id}/bukti', [CustomerController::class, 'lihatBuktiPembayaran'])->name('customer.orders.bukti');
Route::post('/pesanan-saya/{id}/ajukan-batal', [CustomerController::class, 'ajukanPembatalan'])->name('customer.orders.ajukan-batal');

// Halaman Pesanan Cetak (admin)
Route::get('/' . config('admin.path') . '/pesanan-cetak', [\App\Http\Controllers\Admin\PrintOrderController::class, 'index'])->name('admin.print-orders.index');
Route::post('/' . config('admin.path') . '/pesanan-cetak/bulk-delete', [\App\Http\Controllers\Admin\PrintOrderController::class, 'destroyBulk'])->name('admin.print-orders.bulk-delete');
Route::post('/' . config('admin.path') . '/pesanan-cetak/{id}/status', [\App\Http\Controllers\Admin\PrintOrderController::class, 'updateStatus'])->name('admin.print-orders.status');
Route::post('/' . config('admin.path') . '/pesanan-cetak/{id}/cancel', [\App\Http\Controllers\Admin\PrintOrderController::class, 'cancel'])->name('admin.print-orders.cancel');
Route::post('/' . config('admin.path') . '/pesanan-cetak/{id}/setujui-batal', [\App\Http\Controllers\Admin\PrintOrderController::class, 'setujuiPembatalan'])->name('admin.print-orders.setujui-batal');
Route::post('/' . config('admin.path') . '/pesanan-cetak/{id}/tolak-batal', [\App\Http\Controllers\Admin\PrintOrderController::class, 'tolakPembatalan'])->name('admin.print-orders.tolak-batal');
Route::get('/' . config('admin.path') . '/pesanan-cetak/{id}/download', [\App\Http\Controllers\Admin\PrintOrderController::class, 'download'])->name('admin.print-orders.download');
Route::get('/' . config('admin.path') . '/pesanan-cetak/{id}/download-bukti', [\App\Http\Controllers\Admin\PrintOrderController::class, 'downloadBukti'])->name('admin.print-orders.download-bukti');
Route::post('/' . config('admin.path') . '/pesanan-cetak/{id}/konfirmasi-bayar', [\App\Http\Controllers\Admin\PrintOrderController::class, 'konfirmasiPembayaran'])->name('admin.print-orders.konfirmasi-bayar');
Route::post('/' . config('admin.path') . '/pesanan-cetak/{id}/tolak-bayar', [\App\Http\Controllers\Admin\PrintOrderController::class, 'tolakPembayaran'])->name('admin.print-orders.tolak-bayar');
Route::delete('/' . config('admin.path') . '/pesanan-cetak/{id}', [\App\Http\Controllers\Admin\PrintOrderController::class, 'destroy'])->name('admin.print-orders.destroy');

// Halaman Verifikasi Bayar ATK (admin)
Route::get('/' . config('admin.path') . '/verifikasi-atk', [\App\Http\Controllers\Admin\VerifikasiAtkController::class, 'index'])->name('admin.verifikasi-atk.index');
Route::get('/' . config('admin.path') . '/verifikasi-atk/{id}/bukti', [\App\Http\Controllers\Admin\VerifikasiAtkController::class, 'lihatBukti'])->name('admin.verifikasi-atk.bukti');
Route::get('/' . config('admin.path') . '/verifikasi-atk/{id}/download', [\App\Http\Controllers\Admin\VerifikasiAtkController::class, 'downloadBukti'])->name('admin.verifikasi-atk.download');
Route::post('/' . config('admin.path') . '/verifikasi-atk/{id}/konfirmasi', [\App\Http\Controllers\Admin\VerifikasiAtkController::class, 'konfirmasi'])->name('admin.verifikasi-atk.konfirmasi');
Route::post('/' . config('admin.path') . '/verifikasi-atk/{id}/setujui-batal', [\App\Http\Controllers\Admin\VerifikasiAtkController::class, 'setujuiPembatalan'])->name('admin.verifikasi-atk.setujui-batal');
Route::post('/' . config('admin.path') . '/verifikasi-atk/{id}/tolak-batal', [\App\Http\Controllers\Admin\VerifikasiAtkController::class, 'tolakPembatalan'])->name('admin.verifikasi-atk.tolak-batal');
