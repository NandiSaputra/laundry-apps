@extends('layouts.owner')

@section('title', 'Owner Financial Center | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
        <span>Analitik Keuangan</span>
        <form action="{{ route('owner.dashboard') }}" method="GET" class="flex items-center gap-2">
            <select name="period" onchange="this.form.submit()" class="bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] text-slate-700 dark:text-[#92adc9] text-xs font-bold rounded-lg focus:ring-[#137fec] focus:border-[#137fec] block p-2 uppercase tracking-wide">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </form>
    </div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Ringkasan Eksekutif Keuangan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-sm relative overflow-hidden group">
            <h3 class="text-[9px] sm:text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] mb-3 sm:mb-4 italic">Operasional</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-red-500 tracking-tighter italic leading-none">Rp {{ number_format($stats['expense'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-0.5 bg-red-500/10 text-red-500 text-[7px] sm:text-[8px] font-black uppercase tracking-widest rounded border border-red-500/20 italic">Biaya {{ $period == 'today' ? 'Hari Ini' : ($period == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}</span>
            </div>
            <div class="absolute -right-4 -bottom-4 h-20 sm:w-24 w-20 sm:h-24 text-red-500/5 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-7xl sm:text-8xl">trending_down</span>
            </div>
        </div>

        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-sm relative overflow-hidden group">
            <h3 class="text-[9px] sm:text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] mb-3 sm:mb-4 italic">Laba Bersih</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black {{ ($stats['net_profit'] ?? 0) >= 0 ? 'text-green-500' : 'text-red-500' }} tracking-tighter italic leading-none">Rp {{ number_format($stats['net_profit'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-0.5 bg-green-500/10 text-green-500 text-[7px] sm:text-[8px] font-black uppercase tracking-widest rounded border border-green-500/20 italic">Profit {{ $period == 'today' ? 'Hari Ini' : ($period == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}</span>
            </div>
            <div class="absolute -right-4 -bottom-4 h-20 sm:w-24 w-20 sm:h-24 text-green-500/5 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-7xl sm:text-8xl">account_balance</span>
            </div>
        </div>

        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-sm relative overflow-hidden group border-l-4 border-l-[#137fec] sm:col-span-2 lg:col-span-1">
            <h3 class="text-[9px] sm:text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] mb-3 sm:mb-4 italic">Aliran Modal</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tighter italic leading-none">Rp {{ number_format(($stats['income'] ?? 0), 0, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-2 py-0.5 bg-[#137fec]/10 text-[#137fec] text-[7px] sm:text-[8px] font-black uppercase tracking-widest rounded border border-[#137fec]/20 italic">Pendapatan Kotor</span>
            </div>
            <div class="absolute -right-4 -bottom-4 h-20 sm:w-24 w-20 sm:h-24 text-[#137fec]/5 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-7xl sm:text-8xl">query_stats</span>
            </div>
        </div>
    </div>

    <!-- Analytics & Intelligence -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Revenue Intelligence -->
        <div class="lg:col-span-2 bg-white dark:bg-[#111a22] p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest italic">Matriks Alur Pendapatan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-1 italic">Monitoring Performa Institusi</p>
                </div>
                <div class="h-12 w-12 bg-slate-50 dark:bg-[#192633] rounded-2xl flex items-center justify-center text-[#137fec] border border-slate-100 dark:border-[#324d67] shadow-inner">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
            </div>
            <div class="h-80 w-full">
                <canvas id="ownerChart"></canvas>
            </div>
            <div class="mt-8 pt-8 border-t border-slate-50 dark:border-[#324d67] flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.4em] mb-2 italic">Meta Analisis Periode</p>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-[#137fec] shadow-[0_0_8px_rgba(19,127,236,0.6)]"></div>
                            <span class="text-[8px] font-black text-slate-500 uppercase italic">Pendapatan</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
                            <span class="text-[8px] font-black text-slate-500 uppercase italic">Pengeluaran</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-[#137fec] uppercase tracking-widest italic mb-1">Hasil {{ $period == 'today' ? 'Hari Ini' : ($period == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}</p>
                    <p class="text-2xl font-black {{ ($stats['net_profit'] ?? 0) >= 0 ? 'text-green-500' : 'text-red-500' }} tracking-tighter italic">Rp {{ number_format($stats['net_profit'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Inventory Movement -->
        <div class="bg-white dark:bg-[#111a22] p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl">
            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest italic mb-10 flex items-center gap-2">
                <span class="h-1 w-6 bg-[#137fec] rounded-full"></span>
                Popularitas SKU
            </h3>
            <div class="space-y-8">
                @forelse($topServices as $service)
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="h-12 w-12 bg-slate-50 dark:bg-[#233648] rounded-2xl flex items-center justify-center text-slate-400 group-hover:bg-[#137fec] group-hover:text-white transition-all border border-slate-100 dark:border-[#324d67] shadow-sm overflow-hidden">
                            <span class="text-xs font-black italic">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-tight italic">{{ $service->nama }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 bg-slate-100 dark:bg-[#324d67] h-1.5 rounded-full overflow-hidden shadow-inner">
                                    <div class="bg-[#137fec] h-full shadow-[0_0_8px_rgba(19,127,236,0.6)]" style="width: {{ min(100, ($service->total_qty / max(1, ($topServices[0]->total_qty ?? 1))) * 100) }}%"></div>
                                </div>
                                <span class="text-[9px] text-[#137fec] font-black uppercase italic">{{ $service->total_qty }} Qty</span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-20 text-center text-slate-400">
                    <span class="material-symbols-outlined text-6xl opacity-10">inventory_2</span>
                    <p class="text-[9px] font-black uppercase tracking-[0.4em] mt-6 italic">Tidak Ada Intelijen SKU Terdeteksi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Data Stream: Recent Transactions & Expenses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Recent Transactions -->
        <div class="bg-white dark:bg-[#111a22] p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest italic">Aliran Transaksi Terkini</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-1 italic">Monitoring Real-Time Pendapatan</p>
                </div>
                <a href="{{ route('owner.reports') }}" class="text-[10px] font-black text-[#137fec] uppercase tracking-widest hover:underline italic">Lihat Audit</a>
            </div>
            <div class="hidden sm:block overflow-x-auto -mx-8">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-[#192633]/50 border-y border-slate-100 dark:border-[#324d67]">
                            <th class="px-8 py-4 text-[9px] font-black uppercase italic text-slate-400">ID Protokol</th>
                            <th class="px-8 py-4 text-[9px] font-black uppercase italic text-slate-400">Pelanggan</th>
                            <th class="px-8 py-4 text-right text-[9px] font-black uppercase italic text-slate-400">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-[#324d67]">
                        @foreach($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-8 py-4 font-black text-[#137fec] text-[10px] italic">#{{ $trx->kode_transaksi }}</td>
                            <td class="px-8 py-4">
                                <span class="text-[10px] font-black text-slate-800 dark:text-white uppercase italic">{{ $trx->pelanggan->nama }}</span>
                            </td>
                            <td class="px-8 py-4 text-right font-black text-slate-800 dark:text-white text-xs italic">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Transaction List -->
            <div class="sm:hidden -mx-8 divide-y divide-slate-50 dark:divide-[#324d67]">
                @forelse($recentTransactions as $trx)
                <div class="px-8 py-4 flex justify-between items-center transition-all active:bg-slate-50 dark:active:bg-white/[0.02]">
                    <div>
                        <p class="text-[9px] font-black text-[#137fec] italic uppercase">#{{ $trx->kode_transaksi }}</p>
                        <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase italic tracking-tighter mt-0.5">{{ $trx->pelanggan->nama }}</p>
                    </div>
                    <p class="text-xs font-black text-slate-800 dark:text-white italic tracking-tighter">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="px-8 py-10 text-center italic text-[#10px] font-black uppercase text-slate-400">Data Kosong</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="bg-white dark:bg-[#111a22] p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest italic">Aliran Pengeluaran Terkini</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-1 italic">Monitoring Real-Time Biaya</p>
                </div>
                <a href="{{ route('owner.pengeluaran.index') }}" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:underline italic">Lihat Buku Besar</a>
            </div>
            <div class="hidden sm:block overflow-x-auto -mx-8">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-[#192633]/50 border-y border-slate-100 dark:border-[#324d67]">
                            <th class="px-8 py-4 text-[9px] font-black uppercase italic text-slate-400">Tanggal</th>
                            <th class="px-8 py-4 text-[9px] font-black uppercase italic text-slate-400">Deskripsi</th>
                            <th class="px-8 py-4 text-right text-[9px] font-black uppercase italic text-slate-400">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-[#324d67]">
                        @foreach($recentExpenses as $exp)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-8 py-4">
                                <span class="text-[10px] font-black text-slate-400 uppercase italic">{{ $exp->tanggal->format('d/m') }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-[10px] font-black text-slate-800 dark:text-white uppercase italic">{{ $exp->nama }}</span>
                            </td>
                            <td class="px-8 py-4 text-right font-black text-red-500 text-xs italic">
                                Rp {{ number_format($exp->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Expense List -->
            <div class="sm:hidden -mx-8 divide-y divide-slate-50 dark:divide-[#324d67]">
                @forelse($recentExpenses as $exp)
                <div class="px-8 py-4 flex justify-between items-center transition-all active:bg-slate-50 dark:active:bg-white/[0.02]">
                    <div>
                        <p class="text-[8px] font-black text-slate-400 italic uppercase">{{ $exp->tanggal->format('d M') }}</p>
                        <p class="text-[11px] font-black text-slate-800 dark:text-white uppercase italic tracking-tighter mt-0.5">{{ $exp->nama }}</p>
                    </div>
                    <p class="text-xs font-black text-red-500 italic tracking-tighter">Rp {{ number_format($exp->jumlah, 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="px-8 py-10 text-center italic text-[#10px] font-black uppercase text-slate-400">Data Kosong</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ownerChart');
    if (ctx) {
        const isDark = document.documentElement.classList.contains('dark');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($incomeChart['labels']),
                datasets: [
                    {
                        label: 'Pendapatan Kotor',
                        data: @json($incomeChart['income']),
                        borderColor: '#137fec',
                        backgroundColor: 'rgba(19, 127, 236, 0.05)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.45,
                        pointBackgroundColor: '#137fec',
                        pointBorderColor: isDark ? '#111a22' : '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Pengeluaran',
                        data: @json($incomeChart['expenses']),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.05)',
                        borderWidth: 4,
                        fill: true,
                        tension: 0.45,
                        pointBackgroundColor: '#ef4444',
                        pointBorderColor: isDark ? '#111a22' : '#fff',
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#111a22' : '#fff',
                        titleColor: isDark ? '#fff' : '#000',
                        bodyColor: isDark ? '#92adc9' : '#64748b',
                        borderColor: isDark ? '#324d67' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        titleFont: { weight: 'bold', size: 11, family: 'Inter' },
                        bodyFont: { weight: 'medium', size: 10, family: 'Inter' },
                        displayColors: true,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: isDark ? 'rgba(255, 255, 255, 0.03)' : 'rgba(0, 0, 0, 0.03)', drawBorder: false },
                        ticks: {
                            color: isDark ? '#5a7690' : '#64748b',
                            font: { weight: 'bold', size: 9, family: 'Inter' },
                            callback: (v) => 'Rp ' + (v >= 1000000 ? (v/1000000)+'M' : (v >= 1000 ? (v/1000)+'K' : v))
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { 
                            color: isDark ? '#5a7690' : '#64748b', 
                            font: { weight: 'bold', size: 9, family: 'Inter' } 
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
