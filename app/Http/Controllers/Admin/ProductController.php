<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $products = Product::with('category')
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('name_produk', 'LIKE', '%' . $keyword . '%');
            })
            ->paginate(10);

        $categories = Category::orderBy('name')->get();

        return view('admin.produk.index', compact('products', 'keyword', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.produk.create', compact('categories'));
    }

    // 2. Memproses penyimpanan data dan file gambar ke database
    public function store(Request $request)
    {
        // Validasi input data
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $nama_file_gambar = null;

        // Logika upload gambar jika admin memilih file
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $nama_file_gambar = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/produk'), $nama_file_gambar);
        }

        Product::create([
            'name_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'item_type' => 'produk',
            'gambar' => $nama_file_gambar,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk ATK berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $produk = Product::findOrFail($id);

        if ($produk->gambar && file_exists(public_path('images/produk/' . $produk->gambar))) {
            unlink(public_path('images/produk/' . $produk->gambar));
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function edit($id)
    {
        $produk = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.produk.edit', compact('produk', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $produk = Product::findOrFail($id);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && file_exists(public_path('images/produk/' . $produk->gambar))) {
                unlink(public_path('images/produk/' . $produk->gambar));
            }

            $file = $request->file('gambar');
            $nama_file_gambar = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/produk'), $nama_file_gambar);

            $produk->gambar = $nama_file_gambar;
        }

        $produk->name_produk = $request->nama_produk;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->category_id = $request->category_id;
        $produk->save();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Kategori berhasil dihapus!');
    }

}
