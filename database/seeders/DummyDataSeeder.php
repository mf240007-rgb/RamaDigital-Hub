<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Buat pelanggan dummy ─────────────────────────────────────
        $pelanggan = [
            [
                'name'       => 'Budi Santoso',
                'full_name'  => 'Budi Santoso',
                'email'      => 'budi@example.com',
                'whatsapp'   => '081234567890',
                'password'   => Hash::make('password123'),
                'role'       => 'pelanggan',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name'       => 'Siti Aminah',
                'full_name'  => 'Siti Aminah',
                'email'      => 'siti@example.com',
                'whatsapp'   => '082345678901',
                'password'   => Hash::make('password123'),
                'role'       => 'pelanggan',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(20),
            ],
            [
                'name'       => 'Rizky Maulana',
                'full_name'  => 'Rizky Maulana',
                'email'      => 'rizky@example.com',
                'whatsapp'   => '083456789012',
                'password'   => Hash::make('password123'),
                'role'       => 'pelanggan',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
        ];

        $userIds = [];
        foreach ($pelanggan as $data) {
            // Hindari duplikat jika seeder dijalankan ulang
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                $data
            );
            $userIds[$data['name']] = $user->id;
        }

        // ── 2. Buat pesanan cetak dummy ─────────────────────────────────
        $pesananCetak = [
            // Budi — Menunggu Antrean
            [
                'order_number'   => 'RDH-DUMMY-0001',
                'user_id'        => $userIds['Budi Santoso'],
                'item_type'      => 'jasa',
                'file_dokumen'   => null,
                'detail_pesanan' => 'HVS A4, 10 lembar, Hitam & Putih',
                'total_harga'    => 0,
                'status'         => 'Menunggu Antrean',
                'jenis_kertas'   => 'hvs_a4',
                'jumlah_lembar'  => 10,
                'mode_cetak'     => 'hitam_putih',
                'catatan'        => null,
                'created_at'     => now()->subDays(5)->setTime(9, 0),
                'updated_at'     => now()->subDays(5)->setTime(9, 0),
            ],
            // Budi — Diproses
            [
                'order_number'   => 'RDH-DUMMY-0002',
                'user_id'        => $userIds['Budi Santoso'],
                'item_type'      => 'jasa',
                'file_dokumen'   => null,
                'detail_pesanan' => 'Foto Glossy, 5 lembar, Full Color',
                'total_harga'    => 35000,
                'status'         => 'diproses',
                'jenis_kertas'   => 'foto_glossy',
                'jumlah_lembar'  => 5,
                'mode_cetak'     => 'full_color',
                'catatan'        => 'Ukuran 4x6',
                'created_at'     => now()->subDays(4)->setTime(10, 30),
                'updated_at'     => now()->subDays(3)->setTime(8, 0),
            ],
            // Siti — Selesai
            [
                'order_number'   => 'RDH-DUMMY-0003',
                'user_id'        => $userIds['Siti Aminah'],
                'item_type'      => 'jasa',
                'file_dokumen'   => null,
                'detail_pesanan' => 'HVS F4/Folio, 20 lembar, Hitam & Putih',
                'total_harga'    => 20000,
                'status'         => 'selesai',
                'jenis_kertas'   => 'hvs_f4',
                'jumlah_lembar'  => 20,
                'mode_cetak'     => 'hitam_putih',
                'catatan'        => 'Bolak-balik',
                'created_at'     => now()->subDays(7)->setTime(14, 0),
                'updated_at'     => now()->subDays(6)->setTime(11, 0),
            ],
            // Siti — Dibatalkan oleh pelanggan
            [
                'order_number'      => 'RDH-DUMMY-0004',
                'user_id'           => $userIds['Siti Aminah'],
                'item_type'         => 'jasa',
                'file_dokumen'      => null,
                'detail_pesanan'    => 'Foto Matte (Stiker), 3 lembar, Full Color',
                'total_harga'       => 0,
                'status'            => 'dibatalkan',
                'jenis_kertas'      => 'foto_matte',
                'jumlah_lembar'     => 3,
                'mode_cetak'        => 'full_color',
                'catatan'           => null,
                'alasan_pembatalan' => 'Berubah pikiran, file belum siap',
                'dibatalkan_oleh'   => 'pelanggan',
                'cancelled_at'      => now()->subDays(6)->setTime(16, 0),
                'created_at'        => now()->subDays(7)->setTime(8, 0),
                'updated_at'        => now()->subDays(6)->setTime(16, 0),
            ],
            // Rizky — Menunggu Antrean
            [
                'order_number'   => 'RDH-DUMMY-0005',
                'user_id'        => $userIds['Rizky Maulana'],
                'item_type'      => 'jasa',
                'file_dokumen'   => null,
                'detail_pesanan' => 'HVS A4, 50 lembar, Full Color',
                'total_harga'    => 0,
                'status'         => 'Menunggu Antrean',
                'jenis_kertas'   => 'hvs_a4',
                'jumlah_lembar'  => 50,
                'mode_cetak'     => 'full_color',
                'catatan'        => 'Jilid spiral',
                'created_at'     => now()->subDays(1)->setTime(11, 0),
                'updated_at'     => now()->subDays(1)->setTime(11, 0),
            ],
            // Rizky — Dibatalkan oleh admin
            [
                'order_number'      => 'RDH-DUMMY-0006',
                'user_id'           => $userIds['Rizky Maulana'],
                'item_type'         => 'jasa',
                'file_dokumen'      => null,
                'detail_pesanan'    => 'HVS A4, 5 lembar, Hitam & Putih',
                'total_harga'       => 0,
                'status'            => 'dibatalkan',
                'jenis_kertas'      => 'hvs_a4',
                'jumlah_lembar'     => 5,
                'mode_cetak'        => 'hitam_putih',
                'catatan'           => null,
                'alasan_pembatalan' => 'File dokumen tidak dapat dibuka, format tidak didukung',
                'dibatalkan_oleh'   => 'admin',
                'cancelled_at'      => now()->subDays(2)->setTime(14, 30),
                'created_at'        => now()->subDays(3)->setTime(9, 0),
                'updated_at'        => now()->subDays(2)->setTime(14, 30),
            ],
        ];

        foreach ($pesananCetak as $pesanan) {
            // Hindari duplikat berdasarkan order_number
            Order::firstOrCreate(
                ['order_number' => $pesanan['order_number']],
                $pesanan
            );
        }

        $this->command->info('✅ Dummy data berhasil dibuat:');
        $this->command->info('   - 3 pelanggan (Budi Santoso, Siti Aminah, Rizky Maulana)');
        $this->command->info('   - 6 pesanan cetak (Menunggu, Diproses, Selesai, Dibatalkan)');
        $this->command->info('   Login pelanggan: email masing-masing, password: password123');
    }
}
