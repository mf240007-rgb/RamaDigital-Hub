<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    private function buildCartData(): array
    {
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

        return compact('cartItems', 'cartTotal', 'cartCount');
    }

    /**
     * Halaman utama.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->take(10)->get();
        $cartData = $this->buildCartData();

        return response()
            ->view('home', array_merge(compact('products'), $cartData))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
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

        $cartData = $this->buildCartData();

        return view('katalog', compact('products', 'categories', 'selectedCategory', 'search'))
            ->with($cartData);
    }

    /**
     * Cek status pesanan berdasarkan nomor pesanan (tanpa login)
     * Mendukung kedua tipe: jasa cetak & produk ATK
     */
    public function cekStatus(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:30',
        ], [
            'order_number.required' => 'Nomor pesanan wajib diisi.',
        ]);

        $orderNumber = strtoupper(trim($request->order_number));

        // Cari pesanan dari semua tipe (jasa cetak & produk ATK)
        $order = Order::with('user')->where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->to(route('home') . '#cek-status')
                ->with('cek_error', "Nomor pesanan <strong>{$orderNumber}</strong> tidak ditemukan. Pastikan nomor yang kamu masukkan sudah benar.")
                ->with('cek_query', $orderNumber);
        }

        // Simpan sebagai array agar tidak ada masalah serialisasi Eloquent di session
        return redirect()->to(route('home') . '#cek-status')
            ->with('cek_result', [
                'order_number'      => $order->order_number,
                'item_type'         => $order->item_type,
                'nama_pemesan'      => $order->user->full_name ?? $order->user->name ?? null,
                'file_names'        => $order->item_type === 'jasa' ? $order->getDokumenFiles() : [],
                'detail_pesanan'    => $order->detail_pesanan,
                'total_harga'       => $order->total_harga,
                'payment_status'    => $order->payment_status,
                'catatan'           => $order->catatan,
                'catatan_pembayaran'=> $order->catatan_pembayaran,
                'status'            => $order->status,
                'alasan_pembatalan' => $order->alasan_pembatalan,
                'dibatalkan_oleh'   => $order->dibatalkan_oleh,
                'cancelled_at'      => $order->cancelled_at?->toIso8601String(),
                'created_at'        => $order->created_at->toIso8601String(),
                'updated_at'        => $order->updated_at->toIso8601String(),
            ])
            ->with('cek_query', $orderNumber);
    }

    /**
     * Batalkan pesanan oleh pelanggan (via nomor pesanan, tanpa login)
     * Hanya boleh saat status masih "Menunggu Antrean"
     */
    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_number'      => 'required|string|max:30',
            'alasan_pembatalan' => 'required|string|max:500',
        ], [
            'order_number.required'      => 'Nomor pesanan wajib diisi.',
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $orderNumber = strtoupper(trim($request->order_number));

        $order = Order::where('order_number', $orderNumber)
            ->where('item_type', 'jasa')
            ->first();

        if (!$order) {
            return redirect()->to(route('home') . '#cek-status')
                ->with('cek_error', "Nomor pesanan <strong>{$orderNumber}</strong> tidak ditemukan.")
                ->with('cek_query', $orderNumber);
        }

        if ($order->status !== 'Menunggu Antrean') {
            return redirect()->to(route('home') . '#cek-status')
                ->with('cek_query', $orderNumber)
                ->with('cek_result', [
                    'order_number'      => $order->order_number,
                    'detail_pesanan'    => $order->detail_pesanan,
                    'catatan'           => $order->catatan,
                    'status'            => $order->status,
                    'alasan_pembatalan' => $order->alasan_pembatalan,
                    'dibatalkan_oleh'   => $order->dibatalkan_oleh,
                    'cancelled_at'      => $order->cancelled_at?->toIso8601String(),
                    'created_at'        => $order->created_at->toIso8601String(),
                    'updated_at'        => $order->updated_at->toIso8601String(),
                ])
                ->with('cek_error_cancel', 'Pesanan hanya dapat dibatalkan saat masih berstatus <strong>Menunggu Antrean</strong>. Status pesanan kamu saat ini: <strong>' . $order->status . '</strong>.');
        }

        $order->update([
            'status'            => 'dibatalkan',
            'alasan_pembatalan' => $request->alasan_pembatalan,
            'dibatalkan_oleh'   => 'pelanggan',
            'cancelled_at'      => now(),
        ]);

        return redirect()->to(route('home') . '#cek-status')
            ->with('cek_query', $orderNumber)
            ->with('cek_result', [
                'order_number'      => $order->order_number,
                'detail_pesanan'    => $order->detail_pesanan,
                'catatan'           => $order->catatan,
                'status'            => 'dibatalkan',
                'alasan_pembatalan' => $request->alasan_pembatalan,
                'dibatalkan_oleh'   => 'pelanggan',
                'cancelled_at'      => now()->toIso8601String(),
                'created_at'        => $order->created_at->toIso8601String(),
                'updated_at'        => now()->toIso8601String(),
            ])
            ->with('success_cancel', 'Pesanan <strong>' . $orderNumber . '</strong> berhasil dibatalkan.');
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

        // Deteksi jika request body kosong akibat post_max_size PHP terlampaui
        // Ketika ini terjadi, $_POST dan $_FILES kosong padahal ada data yang dikirim
        if (empty($_POST) && empty($_FILES) && $request->server('CONTENT_LENGTH') > 0) {
            $maxPost = ini_get('post_max_size');
            return redirect()->to(route('home') . '#jasa-cetak')
                ->with('error', "Total ukuran file terlalu besar. Batas upload server saat ini adalah {$maxPost}. Coba kurangi jumlah file atau gunakan file yang lebih kecil.")
                ->withInput();
        }

        $uploadedFiles = $request->file('file_dokumen');
        if ($uploadedFiles instanceof UploadedFile) {
            $uploadedFiles = [$uploadedFiles];
        }

        if (!is_array($uploadedFiles)) {
            return redirect()->to(route('home') . '#jasa-cetak')
                ->with('error', 'Pilih minimal satu file dokumen sebelum mengirim pesanan.')
                ->withInput();
        }

        $request->merge(['file_dokumen' => array_values($uploadedFiles)]);

        $validated = $request->validate([
            'jenis_kertas'     => 'required|string|max:50',
            'jumlah_halaman'   => 'required|integer|min:1',
            'jumlah_cetak'     => 'required|integer|min:1',
            'mode_cetak'       => 'required|in:hitam_putih,full_color',
            'intensitas_warna' => 'required_if:mode_cetak,full_color|nullable|in:sedikit_warna,banyak_warna',
            'file_dokumen'     => 'required|array|min:1|max:5',
            'file_dokumen.*'   => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'catatan'          => 'nullable|string|max:500',
        ], [
            'jenis_kertas.required'          => 'Pilih jenis kertas terlebih dahulu.',
            'jumlah_halaman.required'         => 'Jumlah halaman wajib diisi.',
            'jumlah_halaman.min'              => 'Jumlah halaman minimal 1.',
            'jumlah_cetak.required'           => 'Jumlah cetak wajib diisi.',
            'jumlah_cetak.min'                => 'Jumlah cetak minimal 1.',
            'mode_cetak.required'             => 'Pilih mode cetak.',
            'intensitas_warna.required_if'    => 'Pilih intensitas warna untuk mode Full Color.',
            'file_dokumen.required'           => 'Upload minimal satu file dokumen.',
            'file_dokumen.min'                => 'Upload minimal satu file dokumen.',
            'file_dokumen.max'                => 'Maksimal 5 file dapat diunggah sekaligus.',
            'file_dokumen.*.mimes'            => 'Format file harus PDF, Word, Excel, JPG, atau PNG.',
            'file_dokumen.*.max'              => 'Ukuran setiap file maksimal 10 MB.',
        ]);

        // Simpan semua file dokumen ke storage/app/private/dokumen_cetak
        $fileNames = [];
        foreach ($uploadedFiles as $file) {
            $fileName    = time() . '_' . Auth::id() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('dokumen_cetak', $fileName, 'local');
            $fileNames[] = $fileName;
        }

        // ===== HITUNG ESTIMASI HARGA =====
        $jumlahHalaman   = $validated['jumlah_halaman'];
        $jumlahCetak     = $validated['jumlah_cetak'];
        $jenisKertas     = $validated['jenis_kertas'];
        $modeCetak       = $validated['mode_cetak'];
        $intensitasWarna = $validated['intensitas_warna'] ?? null;

        $hargaPerHalaman = 0;

        // Kertas biasa
        if (in_array($jenisKertas, ['hvs_a4', 'hvs_f4'])) {
            if ($modeCetak === 'hitam_putih') {
                $hargaPerHalaman = 1000;
            } else {
                // Full color
                $hargaPerHalaman = ($intensitasWarna === 'sedikit_warna') ? 2000 : 3000;
            }
        }
        // Kertas foto
        elseif (in_array($jenisKertas, ['foto_glossy', 'foto_matte'])) {
            if ($modeCetak === 'hitam_putih') {
                $hargaPerHalaman = 1000; // asumsi H&P di foto sama dengan biasa
            } else {
                $hargaPerHalaman = 5000;
            }
        }

        $estimasiHarga = $hargaPerHalaman * $jumlahHalaman * $jumlahCetak;
        $dpAmount = (int) ceil($estimasiHarga * 0.5);

        // Buat detail pesanan yang mudah dibaca
        $labelKertas = [
            'hvs_a4'      => 'HVS A4',
            'hvs_f4'      => 'HVS F4/Folio',
            'foto_glossy' => 'Foto Glossy',
            'foto_matte'  => 'Foto Matte (Stiker)',
        ];
        $namaKertas    = $labelKertas[$validated['jenis_kertas']] ?? $validated['jenis_kertas'];
        $namaCetak     = $validated['mode_cetak'] === 'hitam_putih' ? 'Hitam & Putih' : 'Full Color';
        
        // Detail pesanan dengan info halaman × cetak
        $detailPesanan = "{$namaKertas}, {$jumlahHalaman} halaman × {$jumlahCetak} cetak, {$namaCetak}";

        $orderNumber = Order::generateOrderNumber();

        Order::create([
            'order_number'     => $orderNumber,
            'user_id'          => Auth::id(),
            'item_type'        => 'jasa',
            'file_dokumen'     => $fileNames[0],          // File pertama di kolom lama (kompatibilitas)
            'file_dokumen_list'=> $fileNames,             // Semua file di kolom baru (JSON)
            'detail_pesanan'   => $detailPesanan,
            'total_harga'      => $estimasiHarga, // Simpan estimasi sebagai total_harga awal, admin bisa edit
            'estimasi_harga'   => $estimasiHarga, // Simpan juga di kolom estimasi untuk tracking
            'dp_amount'        => $dpAmount,
            'payment_status'   => 'menunggu_konfirmasi',
            'status'           => 'Menunggu Antrean',
            'jenis_kertas'     => $validated['jenis_kertas'],
            'jumlah_lembar'    => null, // Field lama, tidak terpakai lagi
            'jumlah_halaman'   => $jumlahHalaman,
            'jumlah_cetak'     => $jumlahCetak,
            'mode_cetak'       => $validated['mode_cetak'],
            'intensitas_warna' => $intensitasWarna,
            'catatan'          => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('home')
            ->with('new_order_number', $orderNumber)
            ->with('new_order_message', 'Pesanan sudah diterima. Silakan bayar DP sebesar Rp ' . number_format($dpAmount, 0, ',', '.') . ' sebelum pesanan diproses admin.')
            ->with('new_order_dp_amount', $dpAmount);
    }
}
