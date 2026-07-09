<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan {{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --warna-utama: #1a73e8;
            --warna-gelap: #1c2b4a;
            --warna-aksen: #ff6d00;
        }

        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .nota-wrapper {
            max-width: 680px;
            margin: 30px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            padding: 40px 44px;
            position: relative;
            overflow: hidden;
        }

        /* Watermark menimpa konten */
        .nota-wrapper::before {
            content: "Rama Digital";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 4.5rem;
            font-weight: 900;
            color: rgba(26, 115, 232, 0.10);
            white-space: nowrap;
            pointer-events: none;
            z-index: 10;
            letter-spacing: 3px;
        }

        .nota-content { position: relative; z-index: 1; }

        .nota-header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .nota-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--warna-gelap);
            letter-spacing: 0.5px;
        }

        .nota-brand span { color: var(--warna-aksen); }

        .nota-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--warna-gelap);
            margin: 0;
        }

        .nota-number {
            font-family: monospace;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--warna-utama);
            letter-spacing: 0.5px;
        }

        .nota-table th {
            font-weight: 600;
            color: #64748b;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            padding: 8px 12px;
            background: #f8fafc;
        }

        .nota-table td {
            border: none;
            border-top: 1px solid #f0f0f0;
            padding: 10px 12px;
            font-size: 0.92rem;
            vertical-align: middle;
        }

        .nota-total-row {
            background: #f0f9ff;
            font-weight: 700;
            font-size: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .nota-footer {
            border-top: 2px dashed #e2e8f0;
            padding-top: 18px;
            margin-top: 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 0.82rem;
        }

        .btn-print {
            background: var(--warna-utama);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 28px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
        }

        .btn-print:hover { background: #1557b0; color: white; }

        /* Print styles */
        @media print {
            body { background: white; }
            .nota-wrapper {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
                padding: 20px 24px;
            }
            .no-print { display: none !important; }
            .nota-wrapper::before { font-size: 4rem; color: rgba(26, 115, 232, 0.12); }
        }
    </style>
</head>
<body>
<div class="nota-wrapper">
    <div class="nota-content">

        {{-- Header --}}
        <div class="nota-header d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="nota-brand mb-1">
                    🖨 Rama<span>Digital</span> Hub
                </div>
                <div style="font-size:0.82rem;color:#64748b;">
                    Jl. Mayor Iskandar No.771, Baturaja Timur<br>
                    WA: 0852-7330-0045
                </div>
            </div>
            <div class="text-end">
                <div class="nota-title mb-1">NOTA PESANAN</div>
                <div class="nota-number">{{ $order->order_number }}</div>
                <div style="font-size:0.8rem;color:#94a3b8;margin-top:4px;">
                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
        </div>

        {{-- Info Pelanggan --}}
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div style="font-size:0.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Pelanggan</div>
                <div class="fw-semibold" style="color:var(--warna-gelap);">{{ $order->user->full_name ?? $order->user->name ?? '-' }}</div>
                <div style="font-size:0.84rem;color:#64748b;">WA: {{ $order->user->whatsapp ?? '-' }}</div>
            </div>
            <div class="col-sm-6 text-sm-end">
                <div style="font-size:0.78rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Jenis Pesanan</div>
                <span class="status-badge" style="background:#f0f4ff;color:#1e40af;">
                    {{ $order->item_type === 'jasa' ? '🖨 Jasa Cetak' : '🛍 Produk ATK' }}
                </span>
            </div>
        </div>

        {{-- Detail Pesanan --}}
        <table class="table nota-table mb-0">
            <thead>
                <tr>
                    <th>Item / Layanan</th>
                    <th class="text-end">Jumlah</th>
                    <th class="text-end">Harga</th>
                </tr>
            </thead>
            <tbody>
                @if($order->item_type === 'produk')
                    @php
                        $items = preg_split('/\s*,\s*/', $order->detail_pesanan ?? '', -1, PREG_SPLIT_NO_EMPTY);
                    @endphp
                    @foreach($items as $item)
                        @php
                            $label = $item;
                            $qty   = '1';
                            if (preg_match('/^(.*?)\s*[x×]\s*(\d+)$/u', trim($item), $m)) {
                                $label = trim($m[1]);
                                $qty   = $m[2];
                            }
                            // Cari harga produk berdasarkan nama
                            $produkHarga = \App\Models\Product::where('name_produk', $label)->value('harga');
                            $subtotalItem = $produkHarga ? $produkHarga * (int)$qty : null;
                        @endphp
                        <tr>
                            <td>{{ $label }}</td>
                            <td class="text-end">{{ $qty }} pcs</td>
                            <td class="text-end">
                                @if($produkHarga)
                                    <span style="color:#64748b;font-size:0.82rem;">Rp {{ number_format($produkHarga, 0, ',', '.') }}/pcs</span><br>
                                    <strong>Rp {{ number_format($subtotalItem, 0, ',', '.') }}</strong>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $order->detail_pesanan }}</td>
                        <td class="text-end">{{ $order->jumlah_cetak ?? 1 }}×</td>
                        <td class="text-end text-muted">—</td>
                    </tr>
                    @php $fileNames = $order->getDokumenDisplayNames(); @endphp
                    @if(!empty($fileNames))
                        <tr>
                            <td colspan="3" style="font-size:0.82rem;color:#64748b;padding-top:4px;">
                                <strong>File:</strong>
                                @foreach($fileNames as $fn)
                                    <span>{{ $fn }}</span>@if(!$loop->last), @endif
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endif

                {{-- Total Row --}}
                <tr class="nota-total-row">
                    <td colspan="2" class="fw-bold">Total Pembayaran</td>
                    <td class="text-end fw-bold" style="color:var(--warna-aksen);font-size:1.05rem;">
                        Rp {{ number_format($order->getDisplayTotalHarga(), 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Catatan perubahan harga (jika harga final berbeda dari estimasi) --}}
        @if($order->item_type === 'jasa' && !empty($order->harga_final) && (int)$order->harga_final > 0 && (int)$order->harga_final !== (int)$order->estimasi_harga && (int)$order->estimasi_harga > 0)
            <div class="mt-3 px-3 py-2 rounded-3 d-flex align-items-start gap-2"
                 style="background:#fffbeb;border:1px solid #fde68a;font-size:0.82rem;">
                <i class="bi bi-exclamation-triangle-fill text-warning flex-shrink-0 mt-1"></i>
                <div style="color:#92400e;">
                    <strong>Catatan:</strong> Harga final (Rp {{ number_format($order->harga_final, 0, ',', '.') }}) berbeda dari estimasi awal
                    (Rp {{ number_format($order->estimasi_harga, 0, ',', '.') }}) karena penyesuaian oleh admin.
                    Total yang tercantum di atas adalah harga final yang telah disepakati.
                </div>
            </div>
        @endif

        {{-- Status Lunas --}}
        <div class="mt-4 text-center">
            <span class="status-badge" style="background:#dcfce7;color:#15803d;font-size:0.9rem;padding:8px 20px;">
                ✅ LUNAS — Pembayaran Telah Dikonfirmasi
            </span>
        </div>

        {{-- Footer --}}
        <div class="nota-footer">
            <div class="mb-1">Nota ini dicetak dari sistem <strong>RamaDigital Hub</strong></div>
            <div>Dicetak pada {{ now()->format('d M Y, H:i') }} WIB &nbsp;·&nbsp; Nomor: <strong>{{ $order->order_number }}</strong></div>
        </div>

    </div>
</div>

{{-- Tombol Print (tidak dicetak) --}}
<div class="text-center mt-3 mb-5 no-print">
    <button onclick="window.print()" class="btn-print">
        🖨 Cetak / Simpan PDF
    </button>
    <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary rounded-pill px-4 ms-2">
        ← Kembali
    </a>
</div>
</body>
</html>
