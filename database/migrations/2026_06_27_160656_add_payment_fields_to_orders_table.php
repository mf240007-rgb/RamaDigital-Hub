<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Status pembayaran
            $table->string('payment_status')->default('belum_bayar')->after('status');
            // Bukti pembayaran (gambar yang diupload pelanggan)
            $table->string('bukti_bayar')->nullable()->after('payment_status');
            // Nominal yang harus dibayar (diisi admin setelah konfirmasi harga)
            $table->integer('harga_final')->nullable()->after('bukti_bayar');
            // Catatan dari admin soal pembayaran
            $table->text('catatan_pembayaran')->nullable()->after('harga_final');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'bukti_bayar', 'harga_final', 'catatan_pembayaran']);
        });
    }
};
