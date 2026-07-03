<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyPaymentSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Ambil atau buat produk yang ada ────────────────────────────
        $produk = Product::first();

        if (!$produk) {
            $produk = Product::create([
                'name_produk' => 'Pulpen Pilot Hitam 0.5',
                'harga'       => 3500,
                'stok'        => 100,
                'item_type'   => 'produk',
            ]);
        }

        // ── 2. Data dummy pelanggan ───────────────────────────────────────
        $pelanggan = [
            [
                'full_name' => 'Siti Rahayu',
                'whatsapp'  => '081234000001',
                'orders'    => [
                    [
                        'items'          => [['id' => $produk->id, 'qty' => 3, 'nama' => $produk->name_produk]],
                        'total'          => $produk->harga * 3,
                        'payment_status' => 'menunggu_konfirmasi',   // ← menunggu verifikasi
                        'status'         => 'Menunggu Antrean',
                        'created_at'     => now()->subMinutes(15),
                    ],
                ],
            ],
            [
                'full_name' => 'Budi Santoso',
                'whatsapp'  => '081234000002',
                'orders'    => [
                    [
                        'items'          => [['id' => $produk->id, 'qty' => 5, 'nama' => $produk->name_produk]],
                        'total'          => $produk->harga * 5,
                        'payment_status' => 'menunggu_konfirmasi',   // ← menunggu verifikasi
                        'status'         => 'Menunggu Antrean',
                        'created_at'     => now()->subMinutes(45),
                    ],
                ],
            ],
            [
                'full_name' => 'Dewi Kurniawati',
                'whatsapp'  => '081234000003',
                'orders'    => [
                    [
                        'items'          => [['id' => $produk->id, 'qty' => 2, 'nama' => $produk->name_produk]],
                        'total'          => $produk->harga * 2,
                        'payment_status' => 'menunggu_konfirmasi',   // ← menunggu verifikasi
                        'status'         => 'Menunggu Antrean',
                        'created_at'     => now()->subHours(2),
                    ],
                ],
            ],
            [
                'full_name' => 'Rizky Maulana',
                'whatsapp'  => '081234000004',
                'orders'    => [
                    [
                        'items'          => [['id' => $produk->id, 'qty' => 10, 'nama' => $produk->name_produk]],
                        'total'          => $produk->harga * 10,
                        'payment_status' => 'lunas',     // ← sudah dikonfirmasi
                        'status'         => 'diproses',
                        'created_at'     => now()->subDay(),
                    ],
                ],
            ],
            [
                'full_name' => 'Andi Pratama',
                'whatsapp'  => '081234000005',
                'orders'    => [
                    [
                        'items'          => [['id' => $produk->id, 'qty' => 1, 'nama' => $produk->name_produk]],
                        'total'          => $produk->harga * 1,
                        'payment_status' => 'belum_bayar',  // ← belum bayar sama sekali
                        'status'         => 'Menunggu Antrean',
                        'created_at'     => now()->subHours(5),
                    ],
                ],
            ],
        ];

        // ── 3. Buat user & order ──────────────────────────────────────────
        foreach ($pelanggan as $data) {
            // Cek apakah sudah ada user dengan nomor WA ini
            $user = User::where('whatsapp', $data['whatsapp'])->first();

            if (!$user) {
                $user = User::create([
                    'name'      => $data['full_name'],
                    'full_name' => $data['full_name'],
                    'email'     => 'dummy_' . $data['whatsapp'] . '@no-email.ramadigital',
                    'whatsapp'  => $data['whatsapp'],
                    'password'  => Hash::make('password123'),
                    'role'      => 'pelanggan',
                ]);
            }

            foreach ($data['orders'] as $od) {
                // Susun detail pesanan
                $detailArr = array_map(
                    fn($it) => $it['nama'] . ' x' . $it['qty'],
                    $od['items']
                );

                // Buat dummy bukti bayar (file teks kecil sebagai placeholder)
                $buktiBayar = null;
                if ($od['payment_status'] === 'menunggu_konfirmasi') {
                    $buktiBayar = 'dummy_bukti_' . $user->id . '_' . time() . '.txt';
                    $dir = storage_path('app/public/bukti_bayar');
                    if (!is_dir($dir)) mkdir($dir, 0755, true);
                    file_put_contents(
                        $dir . '/' . $buktiBayar,
                        '[Dummy] Bukti bayar untuk ' . $data['full_name'] . ' — ' . now()->toDateTimeString()
                    );
                }

                $order = new Order([
                    'order_number'   => Order::generateOrderNumber(),
                    'user_id'        => $user->id,
                    'item_type'      => 'produk',
                    'detail_pesanan' => implode(', ', $detailArr),
                    'total_harga'    => $od['total'],
                    'status'         => $od['status'],
                    'payment_status' => $od['payment_status'],
                    'bukti_bayar'    => $buktiBayar,
                ]);
                $order->created_at = $od['created_at'];
                $order->updated_at = $od['created_at'];
                $order->save();
            }
        }

        $this->command->info('✅ DummyPaymentSeeder selesai:');
        $this->command->info('   • 3 pesanan menunggu konfirmasi pembayaran');
        $this->command->info('   • 1 pesanan sudah lunas (diproses)');
        $this->command->info('   • 1 pesanan belum bayar');
    }
}
