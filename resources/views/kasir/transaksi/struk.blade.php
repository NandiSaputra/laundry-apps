<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #{{ $transaksi->kode_transaksi }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto; /* Thermal paper width */
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm;
            padding: 5mm;
            margin: 0;
            background: #fff;
            color: #000;
            font-size: 10pt;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 3mm 0; }
        .header h1 { font-size: 14pt; margin-bottom: 0; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 1mm 0; font-size: 8pt; }
        .info { margin: 3mm 0; font-size: 8pt; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th { font-weight: normal; text-align: left; }
        .items td { padding: 1mm 0; vertical-align: top; }
        .total-section { margin-top: 3mm; font-size: 9pt; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 1mm; }
        .qr-code { margin: 5mm 0; }
        .footer { font-size: 8pt; margin-top: 5mm; }
        .btn-print {
            background: #000;
            color: #fff;
            padding: 2mm 4mm;
            border: none;
            cursor: pointer;
            margin-bottom: 10mm;
            width: 100%;
            font-family: inherit;
            text-transform: uppercase;
            font-weight: bold;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Cetak Nota</button>
        <button onclick="window.history.back()" class="btn-print" style="background:#666">Kembali</button>
    </div>

    <div class="header text-center">
        <h1>{{ $shopSettings['shop_name'] ?? 'Lavandera' }}</h1>
        <p>{{ $shopSettings['shop_address'] ?? 'Laundry Khas Karyawan' }}</p>
        <p>{{ $shopSettings['shop_phone'] ?? '0812-3456-7890' }}</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <div style="display:flex; justify-content:space-between">
            <span>Nota: #{{ $transaksi->kode_transaksi }}</span>
        </div>
        <div>Tgl: {{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}</div>
        <div>Plg: <strong>{{ strtoupper($transaksi->pelanggan->nama) }}</strong></div>
        <div>Kasir: {{ $transaksi->user->nama }}</div>
    </div>

    <div class="divider"></div>

    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $detail)
                <tr>
                    <td>
                        {{ $detail->nama_layanan }}<br>
                        <small>{{ (float)$detail->jumlah }} {{ $detail->satuan }} @ Rp.{{ number_format($detail->harga_satuan, 0) }}</small>
                    </td>
                    <td class="text-right">Rp.{{ number_format($detail->subtotal, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"></div>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rp.{{ number_format($transaksi->subtotal, 0) }}</span>
        </div>
        @if($transaksi->diskon > 0)
        <div class="total-row">
            <span>Diskon:</span>
            <span>-Rp.{{ number_format($transaksi->diskon, 0) }}</span>
        </div>
        @endif
        <div class="total-row" style="font-weight: bold; font-size: 11pt;">
            <span>TOTAL:</span>
            <span>Rp.{{ number_format($transaksi->total, 0) }}</span>
        </div>
        <div class="divider"></div>
        <div class="total-row">
            <span>Bayar:</span>
            <span>Rp.{{ number_format($transaksi->total_dibayar, 0) }}</span>
        </div>
        <div class="total-row">
            <span>Status:</span>
            <span>{{ strtoupper($transaksi->status_pembayaran) }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="qr-code text-center">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $transaksi->kode_transaksi }}" alt="QR Code">
        <p style="font-size: 7pt; margin-top: 1mm">Scan untuk ambil laundry</p>
    </div>

    <div class="footer text-center">
        <p>Estimasi Selesai:</p>
        <p><strong>{{ $transaksi->tanggal_estimasi->format('d/m/Y H:i') }}</strong></p>
        <div class="divider"></div>
        <p>TERIMA KASIH</p>
        <p>Cucian Bersih, Hari Jadi Happy!</p>
    </div>
</body>
</html>
