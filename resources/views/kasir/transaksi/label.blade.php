<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label - {{ $transaksi->kode_transaksi }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm 100mm; /* Adjusted for typical thermal label */
        }
        body {
            margin: 0;
            padding: 5px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 58mm;
            text-align: center;
        }
        .header {
            border-bottom: 1px dashed #000;
            margin-bottom: 5px;
            padding-bottom: 5px;
        }
        .name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .code {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .details {
            text-align: left;
            font-size: 10px;
            margin-bottom: 5px;
        }
        .footer {
            border-top: 1px dashed #000;
            margin-top: 5px;
            padding-top: 5px;
            font-size: 10px;
        }
        .qr-placeholder {
            margin: 5px auto;
            width: 40mm;
            height: 40mm;
            background: #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            border: 1px solid #ddd;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #fff3cd; padding: 10px; text-align: center; font-family: sans-serif; font-size: 12px;">
        <p>Preview Printer Thermal (58mm)</p>
        <button onclick="window.print()" style="padding: 5px 10px;">Cetak Sekarang</button>
    </div>

    <div class="header">
        <strong>LAUNDRY APP</strong>
    </div>

    <div class="name">{{ $transaksi->pelanggan->nama }}</div>
    <div class="code">{{ $transaksi->kode_transaksi }}</div>

    <div class="details">
        <div>Tgl Masuk: {{ $transaksi->tanggal_masuk->format('d/m/Y H:i') }}</div>
        <div>Estimasi: {{ $transaksi->tanggal_estimasi->format('d/m/Y H:i') }}</div>
        <div style="margin-top: 5px;">
            <strong>Layanan:</strong><br>
            @foreach($transaksi->details as $detail)
                - {{ $detail->jumlah }} {{ $detail->satuan }} {{ $detail->nama_layanan }}<br>
            @endforeach
        </div>
    </div>

    <div class="qr-placeholder">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($transaksi->kode_transaksi) !!}
    </div>

    <div class="footer">
        SIMPAN LABEL INI PADA<br>BUNGKUSAN BARANG
    </div>
</body>
</html>
