<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota {{ $order->order_number ?? 'RDH-'.$order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --accent: #1d4ed8;
            --paper: #ffffff;
            --bg: #f8fafc;
        }

        body {
            background: linear-gradient(180deg, #eef4ff 0%, #f8fafc 18%, #f8fafc 100%);
            color: var(--ink);
            min-height: 100vh;
        }

        .receipt-wrap {
            max-width: 860px;
            margin: 32px auto;
            padding: 0 16px 32px;
        }

        .receipt-card {
            position: relative;
            background: var(--paper);
            border: 1px solid rgba(226, 232, 240, .95);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(15, 23, 42, .08);
        }

        .receipt-header {
            padding: 24px 28px;
            background: linear-gradient(135deg, #eff6ff, #ffffff);
            border-bottom: 1px solid var(--line);
        }

        .watermark {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            opacity: .18;
            transform: rotate(-16deg);
            user-select: none;
            text-align: center;
            z-index: 1;
            padding: 24px;
        }

        .watermark .brand {
            font-size: clamp(2rem, 6.3vw, 4.4rem);
            font-weight: 900;
            letter-spacing: .18em;
            color: #1d4ed8;
            white-space: nowrap;
            text-transform: uppercase;
            text-shadow: 0 1px 0 rgba(255,255,255,.7);
        }

        .watermark .code {
            margin-top: 10px;
            font-size: clamp(.78rem, 2vw, 1rem);
            font-weight: 800;
            letter-spacing: .35em;
            color: #3b82f6;
        }

        .label {
            color: var(--muted);
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .value {
            font-weight: 600;
            line-height: 1.5;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            letter-spacing: .5px;
        }

        .print-bar {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 18px 28px 0;
        }

        .note-grid {
            position: relative;
            z-index: 1;
            padding: 28px;
        }

        .note-box {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 16px 18px;
            height: 100%;
        }

        .footer-line {
            border-top: 1px dashed #cbd5e1;
            margin-top: 20px;
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            color: var(--muted);
            font-size: .85rem;
        }

        @media print {
            body {
                background: #fff;
            }
            .print-bar {
                display: none !important;
            }
            .receipt-wrap {
                margin: 0;
                max-width: none;
                padding: 0;
            }
            .receipt-card {
                border: 0;
                box-shadow: none;
                border-radius: 0;
            }
        }

        @media (max-width: 576px) {
            .receipt-header,
            .note-grid {
                padding: 18px;
            }
            .print-bar {
                padding: 14px 18px 0;
                justify-content: stretch;
            }
            .print-bar .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-wrap">
        <div class="print-bar">
            <button class="btn btn-outline-secondary rounded-pill px-4" onclick="window.close()">Tutup</button>
            <button class="btn btn-primary rounded-pill px-4" onclick="window.print()">Unduh PDF</button>
        </div>

        <div class="receipt-card mt-3">
            <div class="receipt-header position-relative">
                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                    <div>
                        <div class="text-muted text-uppercase fw-bold" style="font-size:.75rem; letter-spacing:.08em;">Nota Pesanan</div>
                        <h1 class="h4 fw-bold mb-0 mt-1">{{ $order->order_number ?? 'RDH-'.$order->id }}</h1>
                    </div>
                    <div class="text-end">
                        <div class="label mb-1">Tanggal</div>
                        <div class="value">{{ $order->created_at->format('d M Y H:i') }} WIB</div>
                    </div>
                </div>
            </div>

            <div class="note-grid position-relative">
                <div class="watermark" aria-hidden="true">
                    <div>
                        <div class="brand">RAMA DIGITAL</div>
                        <div class="code">{{ $order->order_number ?? 'RDH-'.$order->id }}</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="note-box">
                            <div class="label">Nama Pelanggan</div>
                            <div class="value">{{ $order->user?->full_name ?? $order->user?->name ?? 'Pelanggan' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="note-box">
                            <div class="label">Nomor Pesanan</div>
                            <div class="value mono">{{ $order->order_number ?? 'RDH-'.$order->id }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="note-box">
                            <div class="label">Barang yang Dipesan</div>
                            <div class="value">{{ $order->detail_pesanan }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="note-box">
                            <div class="label">Total Harga</div>
                            <div class="value fs-5 fw-bold text-success">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="note-box">
                            <div class="label">Jenis Transaksi</div>
                            <div class="value">{{ $order->item_type === 'jasa' ? 'Jasa Cetak' : 'Produk ATK' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="note-box" style="background:#f8fbff;">
                            <div class="label">Catatan</div>
                            <div class="value">
                                @if(!empty(trim((string) ($order->catatan ?? ''))))
                                    {{ $order->catatan }}
                                @else
                                    Nota ini dihasilkan otomatis oleh sistem dan digunakan sebagai referensi transaksi.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-line">
                    <div>RamaDigital Hub</div>
                    <div>{{ $order->order_number ?? 'RDH-'.$order->id }}</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</body>
</html>