@php
    $totalRevenue = 0;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .table-report {
            border-collapse: collapse;
            width: 100%;
        }
        .table-report th, .table-report td {
            border: 1px solid #000000;
            padding: 5px;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        .header-row {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .shop-name { font-size: 14pt; font-weight: bold; }
        .report-title { font-size: 12pt; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="7" class="shop-name">{{ strtoupper($shopName) }}</td>
        </tr>
        <tr>
            <td colspan="7">{{ $shopAddress }}</td>
        </tr>
        <tr>
            <td colspan="7" class="report-title">LAPORAN TRANSAKSI LAUNDRY</td>
        </tr>
        <tr>
            <td colspan="2">Periode:</td>
            <td colspan="5">
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                @else
                    Semua Data
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">Dicetak pada:</td>
            <td colspan="5">{{ now()->format('d/m/Y H:i') }}</td>
        </tr>
        <tr><td colspan="7"></td></tr> <!-- Spacer -->
    </table>

    <table class="table-report">
        <thead>
            <tr class="header-row">
                <th width="30">NO</th>
                <th width="150">KODE TRANSAKSI</th>
                <th width="120">TANGGAL</th>
                <th width="200">PELANGGAN</th>
                <th width="100">TOTAL</th>
                <th width="100">STATUS</th>
                <th width="120">PEMBAYARAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $trx)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $trx->kode_transaksi }}</td>
                <td class="text-center">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ strtoupper($trx->pelanggan->nama) }}</td>
                <td class="text-right">{{ $trx->total }}</td>
                <td class="text-center">{{ strtoupper($trx->status) }}</td>
                <td class="text-center">{{ strtoupper($trx->status_pembayaran) }}</td>
            </tr>
            @php
                if ($trx->status !== 'batal') {
                    $totalRevenue += $trx->total;
                }
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold" style="background-color: #f2f2f2;">TOTAL PENDAPATAN</td>
                <td class="text-right font-bold" style="background-color: #f2f2f2;">{{ $totalRevenue }}</td>
                <td colspan="2" style="background-color: #f2f2f2;"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right font-bold" style="background-color: #f2f2f2;">TOTAL PENGELUARAN</td>
                <td class="text-right font-bold" style="background-color: #f2f2f2; color: #ff0000;">{{ $expenses->sum('jumlah') }}</td>
                <td colspan="2" style="background-color: #f2f2f2;"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right font-bold" style="background-color: #f2f2f2;">LABA BERSIH</td>
                <td class="text-right font-bold" style="background-color: #f2f2f2;">{{ $totalRevenue - $expenses->sum('jumlah') }}</td>
                <td colspan="2" style="background-color: #f2f2f2;"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
