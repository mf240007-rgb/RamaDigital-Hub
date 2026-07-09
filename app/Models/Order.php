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
        'harga_final',
        'status',
        'payment_status',
        'dp_amount',
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
        // Format: RDH-{YYYYMMDD}-{6 karakter acak alfanumerik}
        $date   = now()->format('Ymd');
        $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        $candidate = 'RDH-' . $date . '-' . $random;

        // Pastikan tidak duplikat (sangat jarang, tapi aman)
        while (static::where('order_number', $candidate)->exists()) {
            $random = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
            $candidate = 'RDH-' . $date . '-' . $random;
        }

        return $candidate;
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

    public function getDpAmount(): int
    {
        if ($this->dp_amount !== null && $this->dp_amount !== '') {
            return (int) $this->dp_amount;
        }

        $baseAmount = (int) ($this->estimasi_harga ?: $this->total_harga ?: 0);

        return $baseAmount > 0 ? (int) ceil($baseAmount * 0.5) : 0;
    }

    public function isDepositConfirmed(): bool
    {
        return in_array($this->payment_status, ['lunas', 'dp_diterima'], true);
    }

    public function isVisibleInAdminQueue(): bool
    {
        if ($this->status === 'dibatalkan') {
            return false;
        }

        if (in_array($this->payment_status, ['dp_diterima', 'lunas', 'sisa_dibayar'], true)) {
            return true;
        }

        return !empty($this->bukti_bayar) && $this->payment_status === 'menunggu_konfirmasi';
    }

    public function getDisplayTotalHarga(): int
    {
        if (!empty($this->harga_final) && (int) $this->harga_final > 0) {
            return (int) $this->harga_final;
        }

        return (int) ($this->total_harga ?: 0);
    }

    public function getRemainingBalance(): int
    {
        $total = (int) ($this->total_harga ?: 0);
        $paid = (int) ($this->dp_amount ?: 0);

        if ($this->payment_status === 'lunas' && $this->total_harga > 0) {
            return 0;
        }

        if ($this->payment_status === 'sisa_dibayar' && $this->total_harga > 0) {
            return 0;
        }

        return max(0, $total - $paid);
    }

    public function paymentStatusLabel(): string
    {
        return match ($this->payment_status) {
            'lunas' => 'Lunas',
            'dp_diterima' => 'DP Diterima',
            'sisa_dibayar' => 'Menunggu Pelunasan Sisa',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'menunggu_persetujuan_batal' => 'Menunggu Persetujuan Batal',
            'ditolak' => 'Ditolak',
            default => 'Belum Bayar',
        };
    }

    public function getCustomerDisplayState(): string
    {
        if ($this->payment_status === 'ditolak') {
            return 'ditolak';
        }

        if ($this->payment_status === 'lunas') {
            return 'lunas';
        }

        if ($this->payment_status === 'dp_diterima') {
            return 'dp_diterima';
        }

        if ($this->payment_status === 'sisa_dibayar') {
            return 'sisa_dibayar';
        }

        if ($this->payment_status === 'menunggu_konfirmasi') {
            return 'menunggu_konfirmasi';
        }

        if ($this->payment_status === 'menunggu_persetujuan_batal') {
            return 'menunggu_persetujuan_batal';
        }

        return $this->status === 'selesai' ? 'selesai' : ($this->status === 'diproses' ? 'diproses' : 'Menunggu Antrean');
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

    /** Kembalikan nama dokumen yang siap ditampilkan ke pelanggan/admin */
    public function getDokumenDisplayNames(): array
    {
        return array_map(function ($fileName) {
            return preg_replace('/^\d+_\d+_[a-f0-9]+_/i', '', (string) $fileName);
        }, $this->getDokumenFiles());
    }

    /**
     * Ringkasan baris yang siap dirender di kartu transaksi.
     * Produk ATK akan diparsing dari detail_pesanan, sedangkan jasa cetak
     * dirangkum dari atribut utama yang memang dipakai untuk menghitung harga.
     */
    public function summaryRows(): array
    {
        if ($this->item_type === 'produk') {
            $rows = [];
            $items = preg_split('/\s*,\s*/', (string) $this->detail_pesanan, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            foreach ($items as $item) {
                $item = trim($item);
                if ($item === '') {
                    continue;
                }

                $label = $item;
                $qtyText = '1 pcs';

                if (preg_match('/^(.*?)[\s]*[x×][\s]*(\d+)$/u', $item, $matches)) {
                    $label = trim($matches[1]);
                    $qtyText = ((int) $matches[2]) . ' pcs';
                }

                $rows[] = [
                    'label' => $label,
                    'value' => $qtyText,
                ];
            }

            return $rows ?: [[
                'label' => $this->detail_pesanan ?: 'Pesanan',
                'value' => '1 pcs',
            ]];
        }

        $labelKertas = [
            'hvs_a4'      => 'HVS A4',
            'hvs_f4'      => 'HVS F4/Folio',
            'foto_glossy' => 'Foto Glossy',
            'foto_matte'  => 'Foto Matte (Stiker)',
        ];

        $labelMode = [
            'hitam_putih' => 'Hitam & Putih',
            'full_color'  => 'Full Color',
        ];

        $rows = [];

        if ($this->jenis_kertas) {
            $rows[] = [
                'label' => 'Kertas',
                'value' => $labelKertas[$this->jenis_kertas] ?? $this->jenis_kertas,
            ];
        }

        if ($this->jumlah_cetak) {
            $rows[] = [
                'label' => 'Cetak',
                'value' => $this->jumlah_cetak . 'x',
            ];
        }

        if ($this->mode_cetak) {
            $rows[] = [
                'label' => 'Mode',
                'value' => $labelMode[$this->mode_cetak] ?? $this->mode_cetak,
            ];
        }

        if ($this->mode_cetak === 'full_color' && $this->intensitas_warna) {
            $rows[] = [
                'label' => 'Warna',
                'value' => $this->intensitas_warna === 'sedikit_warna' ? 'Sedikit' : 'Banyak',
            ];
        }

        return $rows ?: [[
            'label' => 'Ringkasan',
            'value' => $this->detail_pesanan ?: 'Jasa Cetak',
        ]];
    }
}
