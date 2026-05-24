<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('admin.dashboard');
    }

    public function logout()
    {
        session()->forget('is_admin_logged_in');
        session()->forget('admin_username');

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout. Sampai jumpa!');
    }
}
