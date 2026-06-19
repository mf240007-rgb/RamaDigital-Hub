<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    /*
    |-----------------------------------------------------------------
    | DATA ADMIN STATIS (Hardcoded)
    |-----------------------------------------------------------------
    */
    private $adminUsername = 'admin';
    private $adminPassword = 'admin123';

    public function showLoginForm()
    {
        if (session('is_admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $inputUsername = $request->input('username');
        $inputPassword = $request->input('password');

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        if ($inputUsername === $this->adminUsername && $inputPassword === $this->adminPassword) {
            session(['is_admin_logged_in' => true]);
            session(['admin_username' => $inputUsername]);

            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')
            ->with('error', 'Username atau Password salah. Silakan coba lagi.');
    }

    public function dashboard()
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
        }

        $products = Product::all();
        return view('admin.dashboard', compact('products'));
    }

    public function logout()
    {
        session()->forget('is_admin_logged_in');
        session()->forget('admin_username');

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout. Sampai jumpa!');
    }
    
    public function index()
    {
        $products = Product::all(); // Sekarang baris ini tidak akan error lagi
        return view('admin.produk.index', compact('products'));
    }

    public function destroyCustomer($id)
    {
        // Pastikan yang mengakses adalah admin
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin terlebih dahulu.');
        }

        // Cari data user/pelanggan, jika tidak ada akan otomatis error 404
        $customer = User::findOrFail($id);

        // Pastikan yang dihapus memiliki role pelanggan (bukan admin)
        if (in_array($customer->role, ['pelanggan', 'customer'])) {
            $customer->delete();
            return redirect()->back()->with('success', 'Data pelanggan bernama ' . $customer->full_name . ' berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Tidak dapat menghapus akun administrator.');
    }
}
