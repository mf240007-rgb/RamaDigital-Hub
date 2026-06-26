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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate nomor pesanan unik dengan format RDH-YYYYMMDD-XXXX
     */
    public static function generateOrderNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = 'RDH-' . $date . '-';

        // Cari nomor urut tertinggi untuk hari ini
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
}
