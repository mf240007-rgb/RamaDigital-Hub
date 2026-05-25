<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function create()
{
    return view('admin.produk.create');
}

// 2. Memproses penyimpanan data dan file gambar ke database
public function store(Request $request)
{
    // Validasi input data
    $request->validate([
            'nama_produk' => 'required',
        'harga' => 'required|numeric',
        'stok' => 'required|numeric',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
    ]);

    $nama_file_gambar = null;

    // Logika upload gambar jika admin memilih file
    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        // Membuat nama file unik, contoh: 171500123.png
        $nama_file_gambar = time() . '.' . $file->getClientOriginalExtension();
        // Memindahkan file fisik ke folder public/images/produk di proyek Laravelmu
        $file->move(public_path('images/produk'), $nama_file_gambar);
    }

    // Simpan semua data ke database
    \App\Models\Product::create([
        'name_produk' => $request->nama_produk,
        'harga' => $request->harga,
        'stok' => $request->stok,
        'item_type' => 'produk',
        'gambar' => $nama_file_gambar, // Menyimpan nama filenya saja
    ]);

    // Kembalikan ke halaman utama dengan pesan sukses
    return redirect()->route('admin.produk.index')->with('success', 'Produk ATK berhasil ditambahkan!');
}

public function destroy($id)
{
    // 1. Cari produk berdasarkan ID
    $produk = \App\Models\Product::findOrFail($id);

    // 2. Hapus file gambar fisik dari folder public/images/produk jika ada
    if ($produk->gambar && file_exists(public_path('images/produk/' . $produk->gambar))) {
        unlink(public_path('images/produk/' . $produk->gambar));
    }

    // 3. Hapus data dari database
    $produk->delete();

    // 4. Kembalikan ke halaman index dengan pesan sukses
    return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
}

public function edit($id)
{
    // Cari data produk yang mau diedit
    $produk = \App\Models\Product::findOrFail($id);
    
    // Oper data produk ke halaman edit
    return view('admin.produk.edit', compact('produk'));
}

public function update(Request $request, $id)
{
    // 1. Validasi inputan
    $request->validate([
        'nama_produk' => 'required|string|max:255',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $produk = \App\Models\Product::findOrFail($id);

    // 2. Cek apakah admin mengunggah file gambar baru
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama agar tidak menumpuk di server
        if ($produk->gambar && file_exists(public_path('images/produk/' . $produk->gambar))) {
            unlink(public_path('images/produk/' . $produk->gambar));
        }

        // Upload gambar baru
        $file = $request->file('gambar');
        $nama_file_gambar = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/produk'), $nama_file_gambar);
        
        // Pasang nama gambar baru ke objek produk
        $produk->gambar = $nama_file_gambar;
    }

    // 3. Update data lainnya
    $produk->name_produk = $request->nama_produk;
    $produk->harga = $request->harga;
    $produk->stok = $request->stok;
    $produk->save();

    return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
}

public function index(Request $request)
{
    // 1. Ambil kata kunci pencarian dari input bernama 'search'
    $keyword = $request->get('search');

    // 2. Query ke database - UBAH 'nama_produk' MENJADI 'name_produk'
    $products = \App\Models\Product::when($keyword, function ($query) use ($keyword) {
        return $query->where('name_produk', 'LIKE', '%' . $keyword . '%');
    })->paginate(10); 

    // 3. Kirim data produk beserta keyword lama ke view
    return view('admin.produk.index', compact('products', 'keyword'));
}

}
