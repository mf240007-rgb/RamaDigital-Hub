<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tandai pesanan ATK lama yang pembayarannya telah lunas sebagai selesai.
     * Konfirmasi baru sudah melakukan ini di VerifikasiAtkController; migrasi
     * ini hanya menyelaraskan data yang dibuat sebelum perilaku tersebut ada.
     */
    public function up(): void
    {
        DB::table('orders')
            ->where('item_type', 'produk')
            ->where('payment_status', 'lunas')
            ->where('status', 'Menunggu Antrean')
            ->update([
                'status' => 'selesai',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Data status yang telah diselaraskan tidak dikembalikan agar tidak
        // menurunkan pesanan yang memang sudah selesai.
    }
};
