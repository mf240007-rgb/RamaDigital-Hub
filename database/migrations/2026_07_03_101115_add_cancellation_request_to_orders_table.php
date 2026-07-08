<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Alasan permintaan pembatalan dari pelanggan
            $table->text('cancellation_reason')->nullable()->after('alasan_pembatalan');
            // Waktu permintaan pembatalan diajukan
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cancellation_reason', 'cancellation_requested_at']);
        });
    }
};
