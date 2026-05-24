<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Mass assignable attributes for Product
     */
    protected $fillable = [
        'name_produk',
        'harga',
        'stok',
        'item_type',
        'gambar',
    ];
}
