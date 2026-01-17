<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - {{ ($shopSettings['shop_name'] ?? 'LaundryBiz') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .print-container { width: 100%; border: none !important; box-shadow: none !important; margin: 0 !important; }
        }
        @page {
            margin: 1.5cm;
            size: A4;
        }
        .kop-surat { border-bottom: 4px double #1e293b; margin-bottom: 2rem; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 p-8">
    <div class="max-w-4xl mx-auto bg-white p-12 rounded-xl shadow-sm border border-slate-200 print-container">
        <!-- Header / Kop Surat -->
        <div class="flex items-center gap-6 kop-surat pb-6">
            <div class="h-20 w-20 bg-[#137fec] rounded-2xl flex items-center justify-center text-white shrink-0">
                <span class="material-symbols-outlined text-5xl">local_laundry_service</span>
            </div>
            <div class="flex-1 text-center pr-20">
                <h1 class="text-3xl font-extrabold uppercase tracking-tighter text-slate-900 leading-none mb-1">{{ ($shopSettings['shop_name'] ?? 'LaundryBiz') }}</h1>
                <p class="text-sm font-medium text-slate-500 mb-2 italic">Layanan Laundry Professional & Terpercaya</p>
                <div class="text-[10px] text-slate-600 font-medium">
                    <p>{{ $shopSettings['shop_address'] ?? 'Alamat Bengkel/Toko Belum Diatur' }}</p>
                    <p>Telepon: {{ $shopSettings['shop_phone'] ?? '-' }} | Email: {{ $shopSettings['shop_email'] ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-xl font-bold uppercase tracking-[0.2em] text-slate-900">Laporan Transaksi</h2>
            <div class="h-1 w-20 bg-[#137fec] mx-auto mt-2"></div>
        </div>

        <!-- Filter Info -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                <h3 class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Periode Laporan</h3>
                <p class="text-sm font-bold text-slate-700">
                    @if($startDate && $endDate)
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                    @else
                        Semua Data Transaksi
                    @endif
                </p>
            </div>
            <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                <h3 class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Dicetak Pada</h3>
                <p class="text-sm font-bold text-slate-700">{{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden border border-slate-200 rounded-lg mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider">
                        <th class="px-4 py-4 border-r border-slate-800 text-center w-12">No</th>
                        <th class="px-4 py-4 border-r border-slate-800">Kode</th>
                        <th class="px-4 py-4 border-r border-slate-800">Tanggal</th>
                        <th class="px-4 py-4 border-r border-slate-800">Pelanggan</th>
                        <th class="px-4 py-4 border-r border-slate-800 text-right">Total</th>
                        <th class="px-4 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-[11px] divide-y divide-slate-100">
                    @php $totalIncome = 0; @endphp
                    @forelse($transactions as $trx)
                    <tr class="{{ $trx->status == 'batal' ? 'bg-red-50/50' : '' }}">
                        <td class="px-4 py-3 border-r border-slate-100 text-center text-slate-400 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 border-r border-slate-100 font-bold text-[#137fec]">#{{ $trx->kode_transaksi }}</td>
                        <td class="px-4 py-3 border-r border-slate-100">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 border-r border-slate-100 font-semibold uppercase">{{ $trx->pelanggan->nama }}</td>
                        <td class="px-4 py-3 border-r border-slate-100 text-right font-bold {{ $trx->status == 'batal' ? 'line-through text-slate-300' : 'text-slate-900' }}">
                            Rp {{ number_format($trx->total, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center uppercase font-bold text-[9px] tracking-tight">
                            <span class="{{ $trx->status == 'batal' ? 'text-red-500' : ($trx->status == 'selesai' ? 'text-green-600' : 'text-blue-600') }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                    </tr>
                    @if($trx->status != 'batal')
                        @php $totalIncome += $trx->total; @endphp
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400 italic">Tidak ada data transaksi ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(count($transactions) > 0 || count($expenses) > 0)
                <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-bold text-slate-600 uppercase tracking-widest text-[9px]">Total Pendapatan</td>
                        <td class="px-4 py-3 text-right font-bold text-slate-900 text-xs">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                        <td class="bg-white"></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-right font-bold text-slate-600 uppercase tracking-widest text-[9px]">Total Pengeluaran</td>
                        <td class="px-4 py-3 text-right font-bold text-red-600 text-xs">Rp {{ number_format($expenses->sum('jumlah'), 0, ',', '.') }}</td>
                        <td class="bg-white"></td>
                    </tr>
                    <tr class="bg-slate-100">
                        <td colspan="4" class="px-4 py-4 text-right font-black text-slate-900 uppercase tracking-widest text-[10px]">Laba Bersih</td>
                        <td class="px-4 py-4 text-right font-black text-[#137fec] text-sm">Rp {{ number_format($totalIncome - $expenses->sum('jumlah'), 0, ',', '.') }}</td>
                        <td class="bg-white"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <div class="flex justify-between items-start mt-12">
            <div class="text-[9px] text-slate-400 font-medium max-w-[200px]">
                <p>* Laporan ini dihasilkan secara otomatis oleh sistem {{ config('app.name') }}.</p>
                <p>* Pendapatan bersih tidak termasuk transaksi dengan status 'batal'.</p>
            </div>
            <!-- Signature Area -->
            <div class="text-center w-56">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-20">{{ now()->translatedFormat('d F Y') }}</p>
                <div class="border-b border-slate-400 mx-auto w-40 mb-2"></div>
                <p class="text-sm font-bold text-slate-900 uppercase">{{ auth()->user()->nama }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>

    <!-- Print Control -->
    <div class="fixed bottom-8 right-8 no-print flex gap-4">
        <button onclick="window.close()" class="px-6 py-3 bg-white text-slate-600 border border-slate-200 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-50 shadow-lg">Tutup</button>
        <button onclick="window.print()" class="px-8 py-3 bg-[#137fec] text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-[#137fec]/90 shadow-xl shadow-[#137fec]/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-lg">print</span>
            Cetak Laporan
        </button>
    </div>
</body>
</html>

