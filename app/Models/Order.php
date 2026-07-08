<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'item_type',
        'file_dokumen',
        'file_dokumen_list',
        'detail_pesanan',
        'total_harga',
        'status',
        'payment_status',
        'bukti_bayar',
        'catatan_verifikasi',
        'catatan_pembayaran',
        'paid_at',
        'jenis_kertas',
        'jumlah_lembar',
        'jumlah_halaman',
        'jumlah_cetak',
        'intensitas_warna',
        'estimasi_harga',
        'mode_cetak',
        'catatan',
        'alasan_pembatalan',
        'cancellation_reason',
        'cancellation_requested_at',
        'dibatalkan_oleh',
        'cancelled_at',
    ];

    protected $casts = [
        'cancelled_at'                => 'datetime',
        'cancellation_requested_at'   => 'datetime',
        'paid_at'                     => 'datetime',
        'file_dokumen_list'           => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateOrderNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = 'RDH-' . $date . '-';

        $last = static::where('order_number', 'LIKE', $prefix . '%')
            ->orderByDesc('order_number')
            ->first();

        if ($last && preg_match('/-(\d{4})$/', $last->order_number, $matches)) {
            $nextSeq = (int) $matches[1] + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }

    /** Label payment_status yang ramah baca */
    public function paymentLabel(): string
    {
        return $this->paymentBadge()['label'];
    }

    /** Style badge status pembayaran untuk view admin/pelanggan */
    public function paymentBadge(): array
    {
        return match ($this->payment_status) {
            'lunas' => [
                'bg' => '#d1fae5', 'text' => '#065f46',
                'icon' => 'bi-check-circle-fill', 'label' => 'Lunas',
            ],
            'menunggu_konfirmasi' => [
                'bg' => '#fff3cd', 'text' => '#856404',
                'icon' => 'bi-hourglass-split', 'label' => 'Menunggu Konfirmasi',
            ],
            'menunggu_persetujuan_batal' => [
                'bg' => '#fce7f3', 'text' => '#9d174d',
                'icon' => 'bi-clock-history', 'label' => 'Menunggu Persetujuan Batal',
            ],
            'ditolak' => [
                'bg' => '#fee2e2', 'text' => '#991b1b',
                'icon' => 'bi-x-circle-fill', 'label' => 'Ditolak',
            ],
            default => [
                'bg' => '#e2e8f0', 'text' => '#334155',
                'icon' => 'bi-x-circle-fill', 'label' => 'Ditolak',
            ],
        };
    }

    /** Path file bukti bayar di public storage */
    public function buktiBayarPath(): ?string
    {
        if (! $this->bukti_bayar) {
            return null;
        }
        return storage_path('app/public/bukti_bayar/' . $this->bukti_bayar);
    }

    /** Kembalikan semua nama file dokumen sebagai array */
    public function getDokumenFiles(): array
    {
        if (!empty($this->file_dokumen_list)) {
            return $this->file_dokumen_list;
        }
        if (!empty($this->file_dokumen)) {
            return [$this->file_dokumen];
        }
        return [];
    }
}
