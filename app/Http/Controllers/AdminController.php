<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    /*
    |-----------------------------------------------------------------
    | GUARD: Pastikan yang mengakses adalah admin yang sudah login
    |-----------------------------------------------------------------
    */
    private function guardAdmin()
    {
        if (!session('is_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Anda harus login sebagai admin terlebih dahulu.');
        }
        return null;
    }

    /*
    |-----------------------------------------------------------------
    | HELPER: Baca kredensial admin dari file JSON
    |-----------------------------------------------------------------
    */
    private function credentialsPath(): string
    {
        return storage_path('app/private/admin_credentials.json');
    }

    private function getCredentials(): array
    {
        $path = $this->credentialsPath();
        if (!file_exists($path)) {
            return ['username' => 'admin', 'password' => 'admin123'];
        }
        return json_decode(file_get_contents($path), true)
            ?? ['username' => 'admin', 'password' => 'admin123'];
    }

    private function getAdminUsername(): string
    {
        return $this->getCredentials()['username'] ?? 'admin';
    }

    private function getAdminPassword(): string
    {
        return $this->getCredentials()['password'] ?? 'admin123';
    }

    private function saveCredentials(string $username, string $password): void
    {
        file_put_contents(
            $this->credentialsPath(),
            json_encode(['username' => $username, 'password' => $password], JSON_PRETTY_PRINT)
        );
    }

    /*
    |-----------------------------------------------------------------
    | LOGIN
    |-----------------------------------------------------------------
    */
    public function showLoginForm()
    {
        if (session('is_admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        $inputUsername = $request->input('username');
        $inputPassword = $request->input('password');

        if ($inputUsername === $this->getAdminUsername() && $inputPassword === $this->getAdminPassword()) {
            session(['is_admin_logged_in' => true]);
            session(['admin_username' => $inputUsername]);

            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')
            ->with('error', 'Username atau Password salah. Silakan coba lagi.');
    }

    /*
    |-----------------------------------------------------------------
    | DASHBOARD
    |-----------------------------------------------------------------
    */
    public function dashboard()
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        // Stat cards — data real dari DB
        $totalProduk      = \App\Models\Product::count();
        $pesananMasuk     = \App\Models\Order::whereIn('status', ['Menunggu Antrean', 'diproses'])->count();
        $pesananSelesai   = \App\Models\Order::where('status', 'selesai')->count();
        $pendapatanBulan  = \App\Models\Order::where('payment_status', 'lunas')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');

        // Pesanan cetak terbaru (5 terakhir)
        $recentOrders = \App\Models\Order::with('user')
            ->where('item_type', 'jasa')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Pesanan ATK terbaru (5 terakhir)
        $recentAtk = \App\Models\Order::with('user')
            ->where('item_type', 'produk')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Pembayaran menunggu konfirmasi
        $pendingPayment = \App\Models\Order::where('payment_status', 'menunggu_konfirmasi')->count();

        return view('admin.dashboard', compact(
            'totalProduk', 'pesananMasuk', 'pesananSelesai', 'pendapatanBulan',
            'recentOrders', 'recentAtk', 'pendingPayment'
        ));
    }

    /*
    |-----------------------------------------------------------------
    | LOGOUT
    |-----------------------------------------------------------------
    */
    public function logout()
    {
        session()->forget('is_admin_logged_in');
        session()->forget('admin_username');

        return redirect()->route('admin.login')
            ->with('success', 'Anda berhasil logout. Sampai jumpa!');
    }

    /*
    |-----------------------------------------------------------------
    | HALAMAN PENGATURAN AKUN ADMIN
    |-----------------------------------------------------------------
    */
    public function showSettings()
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $request->validate([
            'current_password'      => 'required|string',
            'new_username'          => 'nullable|string|min:3|max:50|alpha_dash',
            'new_password'          => 'nullable|string|min:6|max:100|confirmed',
            'new_password_confirmation' => 'nullable|string',
        ], [
            'current_password.required'  => 'Password saat ini wajib diisi untuk konfirmasi.',
            'new_username.min'           => 'Username minimal 3 karakter.',
            'new_username.alpha_dash'    => 'Username hanya boleh huruf, angka, dan tanda hubung.',
            'new_password.min'           => 'Password baru minimal 6 karakter.',
            'new_password.confirmed'     => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Verifikasi password saat ini
        if ($request->current_password !== $this->getAdminPassword()) {
            return redirect()->back()
                ->with('error', 'Password saat ini tidak benar.')
                ->withInput();
        }

        // Tidak ada perubahan yang dikirim
        if (empty($request->new_username) && empty($request->new_password)) {
            return redirect()->back()
                ->with('error', 'Tidak ada perubahan yang disimpan. Isi username atau password baru.')
                ->withInput();
        }

        // Tentukan nilai baru
        $newUsername = $request->new_username ?: $this->getAdminUsername();
        $newPassword = $request->new_password ?: $this->getAdminPassword();

        // Simpan ke file JSON
        $this->saveCredentials($newUsername, $newPassword);

        // Update session username jika berubah
        session(['admin_username' => $newUsername]);

        return redirect()->route('admin.settings')
            ->with('success', 'Pengaturan akun berhasil diperbarui.');
    }

    /**
     * Helper: update satu atau lebih nilai di file .env
     */
    private function updateEnvValues(array $values): void
    {
        $envPath    = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            // Bungkus nilai dengan tanda kutip jika mengandung spasi atau karakter khusus
            $escaped = (str_contains($value, ' ') || preg_match('/[#=\'"\\\\]/', $value))
                ? '"' . addslashes($value) . '"'
                : $value;

            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Key sudah ada — ganti nilainya
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$escaped}", $envContent);
            } else {
                // Key belum ada — tambahkan di akhir
                $envContent .= "\n{$key}={$escaped}";
            }
        }

        file_put_contents($envPath, $envContent);
    }

    /*
    |-----------------------------------------------------------------
    | PRODUK (legacy helper)
    |-----------------------------------------------------------------
    */
    public function index()
    {
        $products = Product::all();
        return view('admin.produk.index', compact('products'));
    }

    /*
    |-----------------------------------------------------------------
    | HAPUS PELANGGAN
    |-----------------------------------------------------------------
    */
    public function destroyCustomer($id)
    {
        if ($redirect = $this->guardAdmin()) return $redirect;

        $customer = User::findOrFail($id);

        if (in_array($customer->role, ['pelanggan', 'customer'])) {
            $customer->delete();
            return redirect()->back()->with('success', 'Data pelanggan bernama ' . $customer->full_name . ' berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Tidak dapat menghapus akun administrator.');
    }
}
