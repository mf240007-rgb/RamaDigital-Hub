<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name_produk' => 'Buku Tills Sidu 38 Lembar',
                'harga' => 4500,
                'stok' => 50,
                'item_type' => 'produk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_produk' => 'Pulpen Pilot Hitam 0.5',
                'harga' => 3500,
                'stok' => 100,
                'item_type' => 'produk',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name_produk' => 'Penggaris Besi 30cm',
                'harga' => 7000,
                'stok' => 25,
                'item_type' => 'produk',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
