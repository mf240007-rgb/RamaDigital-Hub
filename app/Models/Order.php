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
        'detail_pesanan',
        'total_harga',
        'status',
        'payment_status',
        'bukti_bayar',
        'catatan_verifikasi',
        'paid_at',
        'jenis_kertas',
        'jumlah_lembar',
        'mode_cetak',
        'catatan',
        'alasan_pembatalan',
        'dibatalkan_oleh',
        'cancelled_at',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
        'paid_at'      => 'datetime',
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
        return match($this->payment_status) {
            'belum_bayar'     => 'Belum Bayar',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'lunas'           => 'Lunas',
            default           => ucfirst($this->payment_status),
        };
    }
}
