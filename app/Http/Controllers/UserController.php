<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan form login user
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'full_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'password' => 'required',
        ]);

        $user = User::where('full_name', $credentials['full_name'])
            ->where('whatsapp', $credentials['whatsapp'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('home')->with('success', 'Selamat datang ' . $user->full_name);
        }

        return back()->withErrors([
            'full_name' => 'Nama, WhatsApp, atau password tidak sesuai.',
        ])->withInput($request->only('full_name', 'whatsapp'));
    }

    /**
     * Tampilkan form registrasi user
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['full_name'],
            'email' => 'user_' . uniqid() . '@no-email.ramadigital',
            'full_name' => $validated['full_name'],
            'whatsapp' => $validated['whatsapp'],
            'password' => Hash::make($validated['password']),
            'role' => 'pelanggan',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang ' . $user->full_name);
    }

    /**
     * Proses logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}
