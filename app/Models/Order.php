<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
