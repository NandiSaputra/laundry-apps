<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #{{ $transaksi->kode_transaksi }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm;
            padding: 4mm;
            margin: 0;
            background: #fff;
            color: #000;
            font-size: 9pt;
            line-height: 1.1;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .divider { border-top: 1px dashed #000; margin: 3mm 0; }
        .header h1 { font-size: 13pt; margin-bottom: 2mm; text-transform: uppercase; font-weight: bold; }
        .header p { margin: 1mm 0; font-size: 7.5pt; font-weight: bold; }
        .info { margin: 3mm 0; font-size: 8pt; }
        .info table { width: 100%; border-collapse: collapse; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th { font-weight: bold; text-align: left; font-size: 8pt; padding-bottom: 1mm; border-bottom: 1px solid #000; }
        .items td { padding: 1.5mm 0; vertical-align: top; font-size: 8.5pt; }
        .total-section { margin-top: 2mm; font-size: 9pt; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 0.5mm; }
        .qr-code { margin: 4mm 0; }
        .footer { font-size: 7.5pt; margin-top: 4mm; font-weight: bold; }
        
        .no-print {
            padding: 5mm;
            background: #f1f5f9;
            margin-bottom: 5mm;
            border-radius: 4mm;
            text-align: center;
        }
        .btn-print {
            background: #137fec;
            color: #fff;
            padding: 2.5mm 0;
            border: none;
            cursor: pointer;
            width: 100%;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 9pt;
            border-radius: 2mm;
            margin-bottom: 2mm;
        }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">Cetak Nota</button>
        <button onclick="window.history.back()" class="btn-print" style="background:#64748b">Batal/Kembali</button>
    </div>

    <div class="header text-center">
        <h1>{{ $shopSettings['shop_name'] ?? 'LaundryBiz' }}</h1>
        <p>{{ $shopSettings['shop_address'] ?? 'Professional Care System' }}</p>
        <p>{{ $shopSettings['shop_phone'] ?? '0812-3456-7890' }}</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <table style="width: 100%">
            <tr><td>NO  : #{{ $transaksi->kode_transaksi }}</td></tr>
            <tr><td>TGL : {{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}</td></tr>
            <tr><td>PLG : <strong>{{ strtoupper($transaksi->pelanggan->nama) }}</strong></td></tr>
            <tr><td>KSR : {{ strtoupper($transaksi->user->nama) }}</td></tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="items">
        <table>
            <thead>
                <tr>
                    <th style="width: 70%">Layanan</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $detail)
                <tr>
                    <td>
                        {{ strtoupper($detail->nama_layanan) }}<br>
                        <small>{{ (float)$detail->jumlah }}{{ $detail->satuan }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                    </td>
                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"></div>

    <div class="total-section">
        <div class="total-row">
            <span>SUBTOTAL</span>
            <span>{{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
        </div>
         @if($transaksi->diskon > 0)
        <div class="total-row">
            <span>DISKON</span>
            <span>-{{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="divider" style="border-top:1px solid #000; margin:1mm 0"></div>
         <div class="total-row" style="font-weight: bold; font-size: 10pt;">
            <span>TOTAL TAGIHAN</span>
            <span>{{ number_format($transaksi->total, 0, ',', '.') }}</span>
        </div>
        <div class="total-row" style="margin-top: 1mm">
            <span>DIBAYAR</span>
            <span>{{ number_format($transaksi->total_dibayar, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>STATUS</span>
            <span>{{ strtoupper($transaksi->status_pembayaran) }}</span>
        </div>
    </div>

    <div class="divider"></div>

     <div class="qr-code text-center">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $transaksi->kode_transaksi }}" alt="QR" style="width:30mm">
        <p style="font-size: 6.5pt; margin-top: 1mm">PINDAI UNTUK STATUS PESANAN</p>
    </div>

     <div class="footer text-center">
        <p style="font-size: 7pt">ESTIMASI SELESAI:</p>
        <p style="font-size: 8pt">{{ $transaksi->tanggal_estimasi->format('d/m/Y H:i') }}</p>
        <div class="divider"></div>
        <p>*** TERIMA KASIH ***</p>
        <p>Cucian Bersih, Hari Jadi Happy!</p>
        <p style="font-size: 6.5pt; color:#666; margin-top:2mm">LaundryBiz OS v1.2.0</p>
    </div>
</body>
</html>
