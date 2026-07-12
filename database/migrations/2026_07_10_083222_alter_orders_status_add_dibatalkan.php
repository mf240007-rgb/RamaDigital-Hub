<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom status dari ENUM terbatas menjadi VARCHAR
        // agar bisa menyimpan nilai 'dibatalkan' dan nilai lain di masa depan
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Menunggu Antrean'");
    }

    public function down(): void
    {
        // Kembalikan ke ENUM original (data 'dibatalkan' akan hilang jika ada)
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('Menunggu Antrean','diproses','selesai') NOT NULL DEFAULT 'Menunggu Antrean'");
    }
};
