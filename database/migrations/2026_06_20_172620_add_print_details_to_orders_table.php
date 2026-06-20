<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom detail jasa cetak
            $table->string('jenis_kertas')->nullable()->after('file_dokumen');
            $table->integer('jumlah_lembar')->nullable()->after('jenis_kertas');
            $table->string('mode_cetak')->nullable()->after('jumlah_lembar');
            $table->text('catatan')->nullable()->after('mode_cetak');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['jenis_kertas', 'jumlah_lembar', 'mode_cetak', 'catatan']);
        });
    }
};
