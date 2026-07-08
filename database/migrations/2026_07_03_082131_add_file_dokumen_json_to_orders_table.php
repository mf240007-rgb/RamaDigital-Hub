<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom file_dokumen_list (JSON) untuk mendukung multi-upload dokumen.
     * Kolom lama file_dokumen tetap dipertahankan untuk kompatibilitas data lama.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('file_dokumen_list')->nullable()->after('file_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('file_dokumen_list');
        });
    }
};
