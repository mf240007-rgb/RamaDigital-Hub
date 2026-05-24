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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Menghubungkan pesanan ke pelanggan yang sedang login (Foreign Key)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('item_type', ['jasa', 'produk']);
            $table->string('file_dokumen')->nullable();
            $table->string('detail_pesanan');
            $table->integer('total_harga');
            $table->enum('status', ['Menunggu Antrean', 'diproses', 'selesai'])->default('Menunggu Antrean');
            $table->timestamps();
        });
    }
};
