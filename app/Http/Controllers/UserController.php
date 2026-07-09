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
        if (Auth::check()) {
            return redirect()->route('home')->with('info', 'Anda sudah login.');
        }

        return view('auth.login');
    }

    /**
     * Normalisasi nomor WhatsApp:
     * Input dari form tidak mengandung +62, hanya angka setelah kode negara.
     * Contoh input: "82177737844" → disimpan sebagai "082177737844"
     * Jika user sudah mengetik "0" di depan (misal "082177737844"), tetap valid.
     */
    private function normalizeWhatsapp(string $number): string
    {
        // Hapus semua karakter non-digit (strip, spasi, dll)
        $number = preg_replace('/\D/', '', $number);

        // Jika belum diawali "0", tambahkan "0" di depan
        if (!str_starts_with($number, '0')) {
            $number = '0' . $number;
        }

        return $number;
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('info', 'Anda sudah login.');
        }

        $request->merge([
            'whatsapp' => $this->normalizeWhatsapp($request->input('whatsapp', '')),
        ]);

        $credentials = $request->validate([
            'full_name' => 'required|string|max:255',
            'whatsapp'  => 'required|digits_between:10,13',
            'password' => 'required',
        ]);

        $user = User::where('full_name', $credentials['full_name'])
            ->where('whatsapp', $credentials['whatsapp'])
            ->first();

        if (!$user) {
            return back()->withErrors([
                'full_name' => 'Akun tidak ditemukan. Periksa kembali username dan nomor WhatsApp Anda, atau daftar terlebih dahulu.',
            ])->withInput($request->only('full_name', 'whatsapp'));
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'full_name' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('full_name', 'whatsapp'));
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Cart user sudah tersimpan di key "cart_user_{id}" — tidak perlu aksi tambahan,
        // CartController akan otomatis membacanya via cartKey().

        return redirect()->route('home')->with('success', 'Selamat datang kembali, ' . $user->full_name . '! Senang melihatmu lagi.');
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
/**
     * Proses registrasi user
     */
    public function register(Request $request)
    {
        // Normalisasi nomor WhatsApp sebelum validasi agar cek unique akurat
        $request->merge([
            'whatsapp' => $this->normalizeWhatsapp($request->input('whatsapp', '')),
        ]);

        // 1. Validasi input
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'whatsapp'  => 'required|digits_between:10,13|unique:users,whatsapp',
            'password'  => 'required|string|min:8|confirmed',
        ], [
            'whatsapp.unique'   => 'Nomor WhatsApp ini sudah terdaftar. Tidak boleh mendaftar ganda.',
            'whatsapp.digits_between' => 'Nomor WhatsApp harus terdiri dari 10 sampai 13 angka.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'password.min'      => 'Password minimal harus 8 karakter.',
        ]);

        // 2. Proses pembuatan akun jika lolos validasi unik
        $user = User::create([
            'name'      => $validated['full_name'],
            'email'     => 'user_' . uniqid() . '@no-email.ramadigital',
            'full_name' => $validated['full_name'],
            'whatsapp'  => $validated['whatsapp'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'pelanggan',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang ' . $user->full_name);
    }

    /**
     * Proses logout user
     */
    public function logout(Request $request)
    {
        // Ambil semua data cart per-user dari session sebelum di-invalidate
        // Key cart berbentuk "cart_user_{id}", simpan semua yang cocok
        $cartData = [];
        foreach ($request->session()->all() as $key => $value) {
            if (str_starts_with($key, 'cart_user_') || $key === 'cart_guest') {
                $cartData[$key] = $value;
            }
        }

        Auth::logout();

        // Invalidate untuk keamanan (hapus data auth)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Restore semua cart ke session baru
        foreach ($cartData as $key => $value) {
            $request->session()->put($key, $value);
        }

        return redirect()->route('user.login')->with('success', 'Anda telah logout. Silakan login kembali.');
    }
}
