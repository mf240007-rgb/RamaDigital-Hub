<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_produk');
            $table->integer('harga');
            $table->integer('stok');
            $table->string('item_type')->default('produk');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }
};
