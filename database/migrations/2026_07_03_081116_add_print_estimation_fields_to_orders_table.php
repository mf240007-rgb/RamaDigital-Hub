<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Jumlah halaman dokumen (input manual)
            $table->integer('jumlah_halaman')->nullable()->after('jumlah_lembar');
            // Berapa kali dokumen dicetak (menggantikan "jumlah lembar/eksemplar")
            $table->integer('jumlah_cetak')->nullable()->after('jumlah_halaman');
            // Intensitas warna: sedikit_warna / banyak_warna (hanya berlaku untuk full_color)
            $table->string('intensitas_warna')->nullable()->after('jumlah_cetak');
            // Estimasi harga yang dihitung otomatis saat submit (bisa 0 jika admin belum konfirmasi)
            $table->unsignedBigInteger('estimasi_harga')->default(0)->after('intensitas_warna');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['jumlah_halaman', 'jumlah_cetak', 'intensitas_warna', 'estimasi_harga']);
        });
    }
};
