<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════════
        // 1. TAMBAH KOLOM YANG BELUM ADA DI TABEL YANG SUDAH ADA
        //    (cek dulu sebelum alter agar tidak error duplikat)
        // ══════════════════════════════════════════════════════

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'full_name'))
                $table->string('full_name')->nullable()->after('name');
            if (!Schema::hasColumn('users', 'whatsapp'))
                $table->string('whatsapp')->nullable()->after('full_name');
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id'))
                $table->unsignedBigInteger('category_id')->nullable()->after('gambar');
            if (!Schema::hasColumn('products', 'gambar'))
                $table->string('gambar')->nullable()->after('stok');
        });

        // Pastikan tabel categories ada
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // Tambah kolom orders yang belum ada
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number'))
                $table->string('order_number', 30)->nullable()->unique()->after('id');
            if (!Schema::hasColumn('orders', 'item_type'))
                $table->enum('item_type', ['jasa', 'produk'])->default('produk')->after('user_id');
            if (!Schema::hasColumn('orders', 'file_dokumen'))
                $table->string('file_dokumen')->nullable()->after('item_type');
            if (!Schema::hasColumn('orders', 'file_dokumen_list'))
                $table->json('file_dokumen_list')->nullable()->after('file_dokumen');
            if (!Schema::hasColumn('orders', 'detail_pesanan'))
                $table->string('detail_pesanan')->nullable()->after('file_dokumen_list');
            if (!Schema::hasColumn('orders', 'payment_status'))
                $table->string('payment_status')->default('menunggu_konfirmasi')->after('status');
            if (!Schema::hasColumn('orders', 'bukti_bayar'))
                $table->string('bukti_bayar')->nullable()->after('payment_status');
            if (!Schema::hasColumn('orders', 'catatan_pembayaran'))
                $table->text('catatan_pembayaran')->nullable()->after('bukti_bayar');
            if (!Schema::hasColumn('orders', 'paid_at'))
                $table->timestamp('paid_at')->nullable()->after('catatan_pembayaran');
            if (!Schema::hasColumn('orders', 'jenis_kertas'))
                $table->string('jenis_kertas')->nullable()->after('paid_at');
            if (!Schema::hasColumn('orders', 'jumlah_lembar'))
                $table->integer('jumlah_lembar')->nullable()->after('jenis_kertas');
            if (!Schema::hasColumn('orders', 'jumlah_halaman'))
                $table->integer('jumlah_halaman')->nullable()->after('jumlah_lembar');
            if (!Schema::hasColumn('orders', 'jumlah_cetak'))
                $table->integer('jumlah_cetak')->nullable()->after('jumlah_halaman');
            if (!Schema::hasColumn('orders', 'intensitas_warna'))
                $table->string('intensitas_warna')->nullable()->after('jumlah_cetak');
            if (!Schema::hasColumn('orders', 'estimasi_harga'))
                $table->unsignedBigInteger('estimasi_harga')->default(0)->after('intensitas_warna');
            if (!Schema::hasColumn('orders', 'mode_cetak'))
                $table->string('mode_cetak')->nullable()->after('estimasi_harga');
            if (!Schema::hasColumn('orders', 'catatan'))
                $table->text('catatan')->nullable()->after('mode_cetak');
            if (!Schema::hasColumn('orders', 'alasan_pembatalan'))
                $table->text('alasan_pembatalan')->nullable()->after('catatan');
            if (!Schema::hasColumn('orders', 'cancellation_reason'))
                $table->text('cancellation_reason')->nullable()->after('alasan_pembatalan');
            if (!Schema::hasColumn('orders', 'cancellation_requested_at'))
                $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_reason');
            if (!Schema::hasColumn('orders', 'dibatalkan_oleh'))
                $table->string('dibatalkan_oleh')->nullable()->after('cancellation_requested_at');
            if (!Schema::hasColumn('orders', 'cancelled_at'))
                $table->timestamp('cancelled_at')->nullable()->after('dibatalkan_oleh');
        });

        // ══════════════════════════════════════════════════════
        // 2. BUAT TABEL SERVICES (dari proposal)
        // ══════════════════════════════════════════════════════
        if (!Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('nama_layanan');                      // contoh: "Cetak HVS A4 H&P"
                $table->text('spesifikasi')->nullable();             // detail layanan
                $table->string('jenis_kertas')->nullable();          // hvs_a4, hvs_f4, foto_glossy, dst
                $table->string('mode_cetak')->nullable();            // hitam_putih / full_color
                $table->string('intensitas_warna')->nullable();      // sedikit_warna / banyak_warna
                $table->unsignedInteger('harga_satuan');             // harga per halaman
                $table->boolean('aktif')->default(true);
                $table->timestamps();
            });
        }

        // ══════════════════════════════════════════════════════
        // 3. BUAT TABEL ORDER_DETAILS (dari proposal)
        // ══════════════════════════════════════════════════════
        if (!Schema::hasTable('order_details')) {
            Schema::create('order_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
                $table->integer('jumlah')->default(1);               // qty produk / jumlah halaman
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->string('file_path')->nullable();             // untuk file jasa cetak per item
                $table->timestamps();
            });
        }

        // ══════════════════════════════════════════════════════
        // 4. TAMBAH FOREIGN KEY KE TABEL YANG SUDAH ADA
        //    (cek dulu agar tidak duplikat)
        // ══════════════════════════════════════════════════════

        // orders.user_id → users.id
        $this->addForeignKeyIfNotExists(
            'orders', 'orders_user_id_foreign',
            function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            }
        );

        // products.category_id → categories.id (jika belum ada)
        $this->addForeignKeyIfNotExists(
            'products', 'products_category_id_foreign',
            function (Blueprint $table) {
                $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            }
        );
    }

    public function down(): void
    {
        // Hapus foreign keys
        Schema::table('order_details', fn($t) => $t->dropForeignSafe(['order_id', 'product_id', 'service_id']));
        Schema::table('products', function ($t) {
            if (Schema::hasColumn('products', 'category_id'))
                $t->dropForeign(['category_id']);
        });
        Schema::table('orders', function ($t) {
            $t->dropForeign(['user_id']);
        });

        Schema::dropIfExists('order_details');
        Schema::dropIfExists('services');
    }

    /**
     * Tambah foreign key hanya jika belum ada,
     * dengan menangkap error duplikat secara aman.
     */
    private function addForeignKeyIfNotExists(string $table, string $keyName, \Closure $callback): void
    {
        try {
            Schema::table($table, $callback);
        } catch (\Throwable $e) {
            // Key sudah ada atau kolom tidak valid — skip
        }
    }
};
