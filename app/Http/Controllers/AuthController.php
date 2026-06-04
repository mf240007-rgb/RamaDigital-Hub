<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.passwords.forgot');
    }

    public function showChangePasswordForm()
    {
        return view('auth.passwords.reset');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'Username wajib diisi.',
            'old_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = User::where('name', $validated['username'])
            ->orWhere('email', $validated['username'])
            ->first();

        if (! $user) {
            return back()->withErrors(['username' => 'Akun dengan username tersebut tidak ditemukan.'])->withInput();
        }

        if (! Hash::check($validated['old_password'], $user->password) || $validated['old_password'] !== 'password123') {
            return back()->withErrors(['old_password' => 'Password lama tidak cocok atau bukan password default password123. Mohon pastikan admin telah mereset akun Anda terlebih dahulu.'])->withInput();
        }

        $user->password = $validated['new_password'];
        $user->save();

        return redirect()->route('user.login')
            ->with('success', 'Password berhasil diperbarui. Silakan login menggunakan password baru Anda.');
    }
}
