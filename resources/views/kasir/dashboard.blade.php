@extends('layouts.kasir')

@section('title', 'Dashboard | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Terminal Overview')

@section('content')
<div class="space-y-8">
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat Card: Income Month -->
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex items-center justify-between transition-colors">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-1">Income Month</p>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($stats['income_month'], 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-[#137fec]/10 rounded-xl flex items-center justify-center text-[#137fec]">
                <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
            </div>
        </div>

        <!-- Stat Card: Net Profit Month -->
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex items-center justify-between transition-colors">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-1">Net Profit Month</p>
                <h3 class="text-xl font-bold {{ $stats['net_profit_month'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">Rp {{ number_format($stats['net_profit_month'], 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-green-50 dark:bg-green-500/10 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400">
                <span class="material-symbols-outlined text-3xl">analytics</span>
            </div>
        </div>

        <!-- Stat Card: Expense Today -->
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex items-center justify-between transition-colors">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-1">Expense Today</p>
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($stats['expense_today'], 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center text-red-600 dark:text-red-400">
                <span class="material-symbols-outlined text-3xl">money_off</span>
            </div>
        </div>

        <!-- Stat Card: Net Profit Today -->
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex items-center justify-between transition-colors">
            <div>
                <p class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-1">Net Profit Today</p>
                <h3 class="text-xl font-bold {{ $stats['net_profit_today'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">Rp {{ number_format($stats['net_profit_today'], 0, ',', '.') }}</h3>
            </div>
            <div class="h-12 w-12 bg-blue-50 dark:bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                <span class="material-symbols-outlined text-3xl">account_balance</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Dashboard Widgets -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Action Bar -->
            <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm transition-colors">
                <h3 class="text-xs font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-4">Express Action</h3>
                <a href="{{ route('kasir.transaksi.baru') }}" class="flex items-center justify-center gap-2 w-full py-4 bg-[#137fec] text-white rounded-xl font-bold hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
                    <span class="material-symbols-outlined">add_shopping_cart</span>
                    <span>Buat Transaksi Baru</span>
                </a>
                <div class="mt-4 pt-4 border-t border-slate-50 dark:border-[#1e2d3d] flex items-center justify-center gap-2">
                    <div class="h-1.5 w-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-wider">Shift: Online & Ready</span>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('kasir.pelanggan') }}" class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] transition-all text-center group">
                    <div class="h-12 w-12 bg-slate-50 dark:bg-[#233648] text-slate-400 dark:text-[#5a7690] rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-[#137fec]/10 group-hover:text-[#137fec] transition-colors">
                        <span class="material-symbols-outlined text-2xl">group</span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest">Pelanggan</span>
                </a>
                <a href="{{ route('kasir.layanan') }}" class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] transition-all text-center group">
                    <div class="h-12 w-12 bg-slate-50 dark:bg-[#233648] text-slate-400 dark:text-[#5a7690] rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-[#137fec]/10 group-hover:text-[#137fec] transition-colors">
                        <span class="material-symbols-outlined text-2xl">sell</span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest">Layanan</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden transition-colors">
                <div class="p-6 border-b border-slate-100 dark:border-[#324d67] flex items-center justify-between bg-slate-50/50 dark:bg-[#192633]">
                    <h3 class="font-bold text-slate-800 dark:text-white uppercase tracking-tight text-sm">Recent Activity</h3>
                    <a href="{{ route('kasir.transaksi.index') }}" class="text-[10px] font-bold text-[#137fec] uppercase tracking-widest hover:underline">View All</a>
                </div>
                
                <div class="overflow-x-auto text-slate-800 dark:text-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50 dark:bg-[#192633] text-[10px] uppercase tracking-wider font-bold text-slate-400">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Pelanggan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#324d67]">
                            @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-bold text-[#137fec] uppercase tracking-widest">#{{ $trx->kode_transaksi }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $trx->pelanggan->nama }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $trx->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = [
                                            'selesai' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                            'proses' => 'bg-[#137fec]/10 text-[#137fec]',
                                            'cuci' => 'bg-[#137fec]/10 text-[#137fec]',
                                            'pending' => 'bg-slate-100 text-slate-500 dark:bg-[#233648] dark:text-[#92adc9]',
                                        ][$trx->status] ?? 'bg-slate-100 text-slate-500';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $trx->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-slate-800 dark:text-white">Rp {{ number_format($trx->total, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <span class="material-symbols-outlined text-4xl opacity-20">history</span>
                                        <p class="text-xs italic mt-2">Belum ada aktivitas hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
