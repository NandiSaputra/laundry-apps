@extends('layouts.admin')

@section('title', 'Dashboard | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Ikhtisar Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Stats Grid -->
    <div class="space-y-8">
    <!-- Header with Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">Ikhtisar Dashboard</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Ringkasan performa bisnis Anda.</p>
        </div>
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
            <select name="period" onchange="this.form.submit()" class="bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] text-slate-700 dark:text-[#92adc9] text-sm rounded-lg focus:ring-[#137fec] focus:border-[#137fec] block p-2.5">
                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
            </select>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Orders -->
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] transition-colors duration-200">
            <div class="flex justify-between items-start">
                <p class="text-slate-500 dark:text-[#92adc9] text-sm font-medium leading-normal">Pesanan Aktif</p>
                <div class="p-2 bg-[#137fec]/10 rounded-lg">
                    <span class="material-symbols-outlined text-[#137fec]">local_shipping</span>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-800 dark:text-white">{{ $statusCounts['proses'] ?? 0 }}</p>
            <p class="text-[#0bda5b] text-sm font-medium flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">trending_up</span> Live
            </p>
        </div>

        <!-- Revenue -->
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] transition-colors duration-200">
            <div class="flex justify-between items-start">
                <p class="text-slate-500 dark:text-[#92adc9] text-sm font-medium leading-normal">
                    Pendapatan {{ $period == 'today' ? 'Hari Ini' : ($period == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}
                </p>
                <div class="p-2 bg-[#137fec]/10 rounded-lg">
                    <span class="material-symbols-outlined text-[#137fec]">payments</span>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-800 dark:text-white">Rp {{ number_format($stats['income'], 0, ',', '.') }}</p>
        </div>

        <!-- Expenses -->
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] transition-colors duration-200">
            <div class="flex justify-between items-start">
                <p class="text-slate-500 dark:text-[#92adc9] text-sm font-medium leading-normal">
                    Pengeluaran {{ $period == 'today' ? 'Hari Ini' : ($period == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}
                </p>
                <div class="p-2 bg-red-500/10 rounded-lg">
                    <span class="material-symbols-outlined text-red-500">trending_down</span>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight text-slate-800 dark:text-white">Rp {{ number_format($stats['expense'], 0, ',', '.') }}</p>
            <p class="text-slate-400 text-sm font-medium flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">payments</span> Outflow
            </p>
        </div>

        <!-- Net Profit -->
        <div class="flex flex-col gap-2 rounded-xl p-6 border border-slate-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] transition-colors duration-200">
            <div class="flex justify-between items-start">
                <p class="text-slate-500 dark:text-[#92adc9] text-sm font-medium leading-normal">Net Profit</p>
                <div class="p-2 bg-green-500/10 rounded-lg">
                    <span class="material-symbols-outlined text-green-500">account_balance</span>
                </div>
            </div>
            <p class="text-2xl font-bold leading-tight {{ $stats['net_profit'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}
            </p>
            <p class="text-slate-400 text-sm font-medium flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">calculate</span> Laba Bersih
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Table Section -->
        <div class="xl:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Pesanan Terbaru</h3>
                <a href="{{ route('admin.transaksi.index') }}" class="text-sm font-medium text-[#137fec] hover:underline">Lihat Semua</a>
            </div>
            <!-- Recent Transactions Table (Desktop) / Cards (Mobile) -->
            <div class="hidden sm:block overflow-hidden rounded-xl border border-slate-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] shadow-sm transition-colors duration-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#192633] transition-colors duration-200">
                            <th class="px-4 py-4 text-slate-600 dark:text-white text-xs font-bold uppercase tracking-wider">ID</th>
                            <th class="px-4 py-4 text-slate-600 dark:text-white text-xs font-bold uppercase tracking-wider">Pelanggan</th>
                            <th class="px-4 py-4 text-slate-600 dark:text-white text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-4 text-slate-600 dark:text-white text-xs font-bold uppercase tracking-wider text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#324d67]">
                        @forelse($recentTransactions as $trx)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 text-sm font-bold text-[#137fec]">#{{ $trx->kode_transaksi }}</td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-medium text-slate-800 dark:text-white">{{ $trx->pelanggan->nama }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-[#92adc9]">{{ $trx->created_at->format('d M, H:i') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusStyles = [
                                        'selesai' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
                                        'proses' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300',
                                        'cuci' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
                                        'packing' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-300',
                                        'pending' => 'bg-slate-100 text-slate-600 dark:bg-slate-500/20 dark:text-slate-300'
                                    ];
                                    $statusBg = [
                                        'selesai' => 'bg-green-500',
                                        'proses' => 'bg-amber-500',
                                        'cuci' => 'bg-blue-500',
                                        'packing' => 'bg-purple-500',
                                        'pending' => 'bg-slate-500'
                                    ];
                                    $style = $statusStyles[$trx->status] ?? $statusStyles['pending'];
                                    $dot = $statusBg[$trx->status] ?? $statusBg['pending'];
                                @endphp
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg {{ $style }} flex items-center gap-1 w-fit uppercase">
                                    <span class="size-1.5 rounded-full {{ $dot }}"></span> {{ str_replace('_', ' ', $trx->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm font-bold text-right text-slate-800 dark:text-white">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-slate-400 italic">Tidak ada pesanan terbaru</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="sm:hidden space-y-3">
                @forelse($recentTransactions as $trx)
                <div class="p-4 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-xl shadow-sm space-y-3 transition-colors duration-200">
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold text-[#137fec]">#{{ $trx->kode_transaksi }}</span>
                        @php
                            $style = $statusStyles[$trx->status] ?? $statusStyles['pending'];
                            $dot = $statusBg[$trx->status] ?? $statusBg['pending'];
                        @endphp
                        <span class="px-2 py-0.5 text-[9px] font-black rounded-lg {{ $style }} flex items-center gap-1 uppercase tracking-wider">
                            <span class="size-1 rounded-full {{ $dot }}"></span> {{ str_replace('_', ' ', $trx->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-end">
                        <div class="space-y-0.5">
                            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $trx->pelanggan->nama }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $trx->created_at->format('d M, H:i') }}</p>
                        </div>
                        <p class="text-sm font-black text-slate-800 dark:text-white">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center bg-white dark:bg-[#111a22] border-2 border-dashed border-slate-200 dark:border-[#324d67] rounded-xl text-slate-400 text-xs italic">
                    Belum ada pesanan terbaru.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right Sidebar Widgets -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tindakan Cepat</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.transaksi.baru') }}" class="flex items-center justify-between p-4 rounded-xl bg-[#137fec] text-white hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
                        <span class="flex items-center gap-3 font-semibold">
                            <span class="material-symbols-outlined">add_circle</span> Pesanan Baru
                        </span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a href="{{ route('admin.pelanggan') }}" class="flex items-center justify-between p-4 rounded-xl bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-[#233648] transition-colors shadow-sm">
                        <span class="flex items-center gap-3 font-semibold">
                            <span class="material-symbols-outlined text-[#137fec]">person_add</span> Tambah Pelanggan
                        </span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="flex items-center justify-between p-4 rounded-xl bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-[#233648] transition-colors shadow-sm">
                        <span class="flex items-center gap-3 font-semibold">
                            <span class="material-symbols-outlined text-[#137fec]">receipt_long</span> Laporan Keuangan
                        </span>
                        <span class="material-symbols-outlined">chevron_right</span>
                    </a>
                </div>
            </div>

            <!-- Revenue Overview -->
            <div class="p-6 rounded-xl border border-slate-200 dark:border-[#324d67] bg-white dark:bg-[#111a22] shadow-sm transition-colors duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-800 dark:text-white">Laba Bersih {{ $period == 'today' ? 'Hari Ini' : ($period == 'year' ? 'Tahun Ini' : 'Bulan Ini') }}</h3>
                    <span class="text-xs text-slate-500 dark:text-[#92adc9]">Analisis Periode Terpilih</span>
                </div>
                
                <div class="h-40 sm:h-48 mt-4">
                    <canvas id="weeklyRevenueChart"></canvas>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-[#324d67]">
                    <div class="flex justify-between items-center text-xs">
                        <p class="font-medium text-slate-500 dark:text-[#92adc9]">Laba Bersih</p>
                        <p class="font-bold {{ $stats['net_profit'] >= 0 ? 'text-[#10b981]' : 'text-red-500' }}">Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('weeklyRevenueChart');
        if(!ctx) return;

        // Extracting data from incomeChart
        const labels = @json($incomeChart['labels']);
        const incomeData = @json($incomeChart['income']);
        const expenseData = @json($incomeChart['expenses']);
        const profitData = @json($incomeChart['net_profit']);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pendapatan',
                        data: incomeData,
                        backgroundColor: '#137fec',
                        borderRadius: 4,
                        hoverBackgroundColor: '#0bda5b',
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenseData,
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        hoverBackgroundColor: '#f87171',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111a22',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        display: false
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#92adc9',
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
