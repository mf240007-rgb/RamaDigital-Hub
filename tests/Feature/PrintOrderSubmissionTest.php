<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrintOrderSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_multi_file_print_order_can_be_submitted(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('cetak.submit'), [
            'jenis_kertas' => 'hvs_a4',
            'jumlah_halaman' => 5,
            'jumlah_cetak' => 2,
            'mode_cetak' => 'hitam_putih',
            'catatan' => 'Uji multi upload',
            'file_dokumen' => [
                UploadedFile::fake()->create('dokumen1.pdf', 200, 'application/pdf'),
                UploadedFile::fake()->create('dokumen2.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
            ],
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('new_order_number');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_type' => 'jasa',
            'status' => 'Menunggu Antrean',
        ]);

        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotEmpty($order->file_dokumen_list);
        $this->assertCount(2, $order->getDokumenFiles());
    }
}
