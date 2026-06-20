<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Halaman Utama (Home)
     * Menampilkan produk terbaru secara terbatas (untuk Slider) dan data Keranjang
     */
    public function index()
    {
        // PERBAIKAN: Mengambil produk terbaru terbatas (8 produk) agar hemat ruang di Home
        $products = Product::with('category')->latest()->take(8)->get();

        // LOGIKA ASLI KERANJANG KAMU (Tetap dipertahankan agar tidak rusak)
        $cartKey   = Auth::check() ? 'cart_user_' . Auth::id() : 'cart_guest';
        $cart      = session($cartKey, []);
        $cartCount = count($cart);
        $cartItems = [];
        $cartTotal = 0;

        if (!empty($cart)) {
            $cartProducts = Product::whereIn('id', array_keys($cart))->get();

            foreach ($cartProducts as $product) {
                $quantity = $cart[$product->id] ?? 0;
                $subtotal = $product->harga * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $cartTotal += $subtotal;
            }
        }

        // Mengirim data produk terbatas dan keranjang ke halaman depan
        return view('home', compact('products', 'cartItems', 'cartTotal', 'cartCount'));
    }

    /**
     * FUNGSI BARU: Halaman Khusus Katalog Produk ATK (Langkah A)
     */
    public function katalog(Request $request)
    {
        // 1. Ambil semua kategori untuk tombol filter di atas
        $categories = Category::orderBy('name')->get();

        // 2. Cek apakah pembeli sedang menyaring kategori tertentu
        $selectedCategory = $request->get('category');

        // 3. Ambil kata kunci pencarian produk
        $search = $request->get('search');

        // 4. Ambil data produk dengan sistem Pagination (12 produk per halaman)
        $products = Product::with('category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->whereHas('category', function ($q) use ($selectedCategory) {
                    $q->where('name', $selectedCategory);
                });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name_produk', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'LIKE', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(12);

        // LOGIKA KERANJANG (Diperlukan juga di halaman katalog agar tombol beli berfungsi)
        $cartKey   = Auth::check() ? 'cart_user_' . Auth::id() : 'cart_guest';
        $cart      = session($cartKey, []);
        $cartCount = count($cart); // Jumlah jenis produk, bukan total qty
        $cartItems = [];
        $cartTotal = 0;

        if (!empty($cart)) {
            $cartProducts = Product::whereIn('id', array_keys($cart))->get();

            foreach ($cartProducts as $product) {
                $quantity = $cart[$product->id] ?? 0;
                $subtotal = $product->harga * $quantity;
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
                $cartTotal += $subtotal;
            }
        }

        return view('katalog', compact('products', 'categories', 'selectedCategory', 'search', 'cartItems', 'cartTotal', 'cartCount'));
    }

    /**
     * Proses submit formulir Jasa Cetak Dokumen
     */
    public function submitCetak(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')
                ->with('error', 'Silakan login terlebih dahulu untuk memesan jasa cetak.');
        }

        $validated = $request->validate([
            'jenis_kertas'  => 'required|string|max:50',
            'jumlah'        => 'required|integer|min:1',
            'mode_cetak'    => 'required|in:hitam_putih,full_color',
            'file_dokumen'  => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'catatan'       => 'nullable|string|max:500',
        ], [
            'jenis_kertas.required' => 'Pilih jenis kertas terlebih dahulu.',
            'jumlah.required'       => 'Jumlah lembar wajib diisi.',
            'jumlah.min'            => 'Jumlah lembar minimal 1.',
            'mode_cetak.required'   => 'Pilih mode cetak.',
            'file_dokumen.required' => 'File dokumen wajib diunggah.',
            'file_dokumen.mimes'    => 'Format file harus PDF, Word, JPG, atau PNG.',
            'file_dokumen.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        // Simpan file dokumen ke storage/app/private/dokumen_cetak (disk local Laravel 11)
        $file      = $request->file('file_dokumen');
        $fileName  = time() . '_' . Auth::id() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->storeAs('private/dokumen_cetak', $fileName, 'local');

        // Buat detail pesanan yang mudah dibaca
        $labelKertas = [
            'hvs_a4'      => 'HVS A4',
            'hvs_f4'      => 'HVS F4/Folio',
            'foto_glossy' => 'Foto Glossy',
            'foto_matte'  => 'Foto Matte (Stiker)',
        ];
        $namaKertas    = $labelKertas[$validated['jenis_kertas']] ?? $validated['jenis_kertas'];
        $namaCetak     = $validated['mode_cetak'] === 'hitam_putih' ? 'Hitam & Putih' : 'Full Color';
        $detailPesanan = "{$namaKertas}, {$validated['jumlah']} lembar, {$namaCetak}";

        Order::create([
            'user_id'       => Auth::id(),
            'item_type'     => 'jasa',
            'file_dokumen'  => $fileName,
            'detail_pesanan'=> $detailPesanan,
            'total_harga'   => 0, // Admin akan konfirmasi harga via WhatsApp
            'status'        => 'Menunggu Antrean',
            'jenis_kertas'  => $validated['jenis_kertas'],
            'jumlah_lembar' => $validated['jumlah'],
            'mode_cetak'    => $validated['mode_cetak'],
            'catatan'       => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('home')
            ->with('success', 'Pesanan cetak berhasil dikirim! Tim kami akan segera menghubungi kamu via WhatsApp untuk konfirmasi harga dan estimasi pengerjaan.');
    }
}