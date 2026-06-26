<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ubah enum status agar mencakup 'dibatalkan'
            // SQLite tidak mendukung ALTER COLUMN untuk enum, jadi kita pakai string
            $table->string('status')->default('Menunggu Antrean')->change();

            // Kolom alasan pembatalan
            $table->text('alasan_pembatalan')->nullable()->after('catatan');

            // Siapa yang membatalkan: 'admin' | 'pelanggan'
            $table->string('dibatalkan_oleh')->nullable()->after('alasan_pembatalan');

            // Waktu pembatalan
            $table->timestamp('cancelled_at')->nullable()->after('dibatalkan_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['alasan_pembatalan', 'dibatalkan_oleh', 'cancelled_at']);
            $table->enum('status', ['Menunggu Antrean', 'diproses', 'selesai'])
                  ->default('Menunggu Antrean')->change();
        });
    }
};
