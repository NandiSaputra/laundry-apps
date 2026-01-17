@php
    $routePrefix = request()->is('owner/*') ? 'owner' : 'admin';
@endphp

@extends("layouts.$routePrefix")

@section('title', 'Laporan Transaksi | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Laporan Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header Summary & Actions -->
    <div class="bg-white dark:bg-[#111a22] p-6 lg:p-8 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight italic">Laporan Keuangan</h1>
            <p class="text-[10px] text-slate-500 dark:text-[#92adc9] mt-1 font-black uppercase tracking-widest italic">Buku besar audit pendapatan & pengeluaran institusi.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 w-full md:w-auto">
            <a href="{{ route($routePrefix . '.reports.export-excel', request()->all()) }}" data-no-loader class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 sm:px-5 py-3 bg-green-500 text-white rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-500/20 group">
                <span class="material-symbols-outlined text-base sm:text-lg group-hover:bounce">download</span>
                <span class="whitespace-nowrap">Ekspor Excel</span>
            </a>
            <a href="{{ route($routePrefix . '.reports.print', request()->all()) }}" target="_blank" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 sm:px-5 py-3 bg-[#137fec] text-white rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest hover:bg-[#137fec]/90 transition-all shadow-xl shadow-[#137fec]/20 group">
                <span class="material-symbols-outlined text-base sm:text-lg group-hover:rotate-12">print</span>
                <span class="whitespace-nowrap">Cetak PDF</span>
            </a>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white dark:bg-[#111a22] p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl transition-colors relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#137fec]/5 rounded-bl-full -mr-16 -mt-16"></div>
        <form action="{{ route($routePrefix . '.reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-8 items-end relative z-10">
            <div class="md:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Awal Periode</label>
                    <div class="relative">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl px-5 py-4 text-sm font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all italic tracking-tighter shadow-inner">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Akhir Periode</label>
                    <div class="relative">
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl px-5 py-4 text-sm font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all italic tracking-tighter shadow-inner">
                    </div>
                </div>
            </div>
            <div class="md:col-span-4 flex gap-4">
                <button type="submit" class="flex-1 py-4 bg-[#137fec] text-white rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-[#137fec]/90 shadow-2xl shadow-[#137fec]/30 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined text-xl">query_stats</span>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route($routePrefix . '.reports') }}" class="flex-1 py-4 bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] rounded-xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 dark:hover:bg-[#2d445b] transition-all text-center flex items-center justify-center italic">
                    Hapus
                </a>
            </div>
        </form>
    </div>

    <!-- Results Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl border-l-4 border-l-[#137fec]">
            <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.3em] mb-2 sm:mb-3 italic">Total Nilai Manifest</h3>
            <p class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tighter italic leading-none">Rp {{ number_format($transactions->filter(fn($t) => $t->status !== 'batal')->sum('total'), 0, ',', '.') }}</p>
            <div class="mt-4 h-1 w-10 sm:w-12 bg-[#137fec] rounded-full"></div>
        </div>
        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl border-l-4 border-l-red-500">
            <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.3em] mb-2 sm:mb-3 italic">Pengeluaran Modal</h3>
            <p class="text-2xl sm:text-3xl font-black text-red-500 tracking-tighter italic leading-none">Rp {{ number_format($expenses->sum('jumlah'), 0, ',', '.') }}</p>
            <div class="mt-4 h-1 w-10 sm:w-12 bg-red-500 rounded-full"></div>
        </div>
        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl border-l-4 border-l-green-500">
            <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.3em] mb-2 sm:mb-3 italic">Hasil Bersih Fiskal</h3>
            @php
                $revenue = $transactions->filter(fn($t) => $t->status !== 'batal')->sum('total');
                $expenseTotal = $expenses->sum('jumlah');
                $netProfit = $revenue - $expenseTotal;
            @endphp
            <p class="text-2xl sm:text-3xl font-black {{ $netProfit >= 0 ? 'text-green-500' : 'text-red-500' }} tracking-tighter italic leading-none">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </p>
            <div class="mt-4 h-1 w-10 sm:w-12 bg-green-500 rounded-full"></div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="bg-white dark:bg-[#111a22] rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-2xl overflow-hidden transition-colors">
        <div class="p-8 border-b border-slate-50 dark:border-[#324d67] bg-slate-50/30 dark:bg-[#192633]/30 flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-widest italic">Dataset Operasional</h3>
                <p class="text-[8px] font-black text-slate-400 uppercase mt-1 tracking-[0.4em] italic">Catatan transaksi yang diaudit untuk periode yang dipilih</p>
            </div>
            <div class="h-10 w-10 bg-white dark:bg-[#233648] rounded-xl flex items-center justify-center text-slate-400 border border-slate-100 dark:border-[#324d67] shadow-sm">
                <span class="material-symbols-outlined">data_usage</span>
            </div>
        </div>
        
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white dark:bg-[#192633] border-b border-slate-50 dark:border-[#324d67]">
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">ID Protokol</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Data Temporal</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Entitas</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Status Terminal</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic text-right">Total Manifest</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-[#324d67]">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors group {{ $trx->status == 'batal' ? 'opacity-30 grayscale' : '' }}">
                        <td class="px-8 py-5 text-sm font-black text-[#137fec] italic tracking-widest uppercase">
                            #{{ $trx->kode_transaksi }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-black text-slate-800 dark:text-white italic tracking-tighter">{{ $trx->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-[8px] text-slate-400 font-black uppercase tracking-[0.2em] mt-0.5 italic">{{ $trx->created_at->format('H:i') }} Z</div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-9 w-9 bg-slate-100 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl flex items-center justify-center text-slate-400 font-black text-[10px] uppercase shadow-inner group-hover:bg-[#137fec] group-hover:text-white transition-all italic">
                                    {{ strtoupper(substr($trx->pelanggan->nama, 0, 1)) }}
                                </div>
                                <span class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-tight italic">{{ $trx->pelanggan->nama }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $s = [
                                    'selesai' => ['bg' => 'bg-green-500/10', 'text' => 'text-green-500', 'border' => 'border-green-500/20'],
                                    'proses' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-500', 'border' => 'border-blue-500/20'],
                                    'cuci' => ['bg' => 'bg-cyan-500/10', 'text' => 'text-cyan-500', 'border' => 'border-cyan-500/20'],
                                    'pending' => ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-500', 'border' => 'border-slate-500/20'],
                                    'batal' => ['bg' => 'bg-red-500/10', 'text' => 'text-red-500', 'border' => 'border-red-500/20'],
                                ][$trx->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'border' => 'border-slate-200'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest italic border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                                {{ str_replace('_', ' ', $trx->status) }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="text-sm font-black text-slate-800 dark:text-white italic tracking-tighter {{ $trx->status == 'batal' ? 'line-through' : '' }}">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-24 text-center text-slate-400 italic text-[10px] uppercase font-bold tracking-widest">Dataset Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Results view -->
        <div class="lg:hidden divide-y divide-slate-50 dark:divide-[#324d67]">
            @forelse($transactions as $trx)
            <div class="p-6 space-y-4 active:bg-slate-50 dark:active:bg-white/[0.02] transition-colors {{ $trx->status == 'batal' ? 'opacity-30' : '' }}">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-[10px] font-black text-[#137fec] italic tracking-widest uppercase">#{{ $trx->kode_transaksi }}</span>
                        <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight italic mt-1">{{ $trx->pelanggan->nama }}</h4>
                        <p class="text-[8px] text-slate-400 font-black uppercase tracking-widest mt-1 italic">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @php
                        $s = [
                            'selesai' => ['bg' => 'bg-green-500/10', 'text' => 'text-green-500', 'border' => 'border-green-500/20'],
                            'proses' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-500', 'border' => 'border-blue-500/20'],
                            'cuci' => ['bg' => 'bg-cyan-500/10', 'text' => 'text-cyan-500', 'border' => 'border-cyan-500/20'],
                            'pending' => ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-500', 'border' => 'border-slate-500/20'],
                            'batal' => ['bg' => 'bg-red-500/10', 'text' => 'text-red-500', 'border' => 'border-red-500/20'],
                        ][$trx->status] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-500', 'border' => 'border-slate-200'];
                    @endphp
                    <span class="px-2.5 py-1 rounded-lg text-[7px] font-black uppercase tracking-widest italic border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                        {{ str_replace('_', ' ', $trx->status) }}
                    </span>
                </div>
                <div class="bg-slate-50 dark:bg-[#192633] p-3 rounded-xl border border-slate-100 dark:border-[#324d67] flex justify-between items-center">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Nilai Manifest</span>
                    <span class="text-xs font-black text-slate-800 dark:text-white italic tracking-tighter {{ $trx->status == 'batal' ? 'line-through' : '' }}">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic">Tidak Ada Hasil Deteksi</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
