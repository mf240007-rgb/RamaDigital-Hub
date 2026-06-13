<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tambahkan baris kode di bawah ini untuk memberi izin pengisian kolom
    protected $fillable = [
        'name_produk',
        'harga',
        'stok',
        'item_type',
        'gambar',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
