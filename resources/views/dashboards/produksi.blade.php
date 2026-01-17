@extends('layouts.admin')

@section('title', 'Produksi Terminal | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Dashboard Produksi')

@section('content')
<div class="space-y-8">
    <!-- Ringkasan Eksekutif Produksi -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Pusat Operasional Aktif</h2>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium italic">Selamat datang kembali, {{ Auth::user()->nama }}. Sistem sedang memonitor antrean.</p>
        </div>
        <div class="flex items-center w-full md:w-auto">
            <a href="{{ route('produksi.transaksi.index') }}" class="w-full md:w-auto px-6 py-3.5 bg-[#137fec] text-white rounded-2xl text-[10px] font-black hover:bg-[#137fec]/90 shadow-2xl shadow-[#137fec]/30 transition-all flex items-center justify-center gap-3 uppercase tracking-[0.2em] group">
                <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">list_alt</span>
                <span>Akses Antrean Ruang Kerja</span>
            </a>
        </div>
    </div>

    <!-- Live Operational Matrix -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $statuses = [
                'proses'  => ['label' => 'Entri / Intake', 'icon' => 'dynamic_feed', 'color' => '#137fec'],
                'cuci'    => ['label' => 'Siklus Cuci', 'icon' => 'waves', 'color' => '#06b6d4'],
                'setrika' => ['label' => 'Tahap Setrika', 'icon' => 'iron', 'color' => '#f59e0b'],
                'packing' => ['label' => 'Packing Akhir', 'icon' => 'inventory_2', 'color' => '#8b5cf6'],
            ];
        @endphp

        @foreach($statuses as $key => $val)
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-sm flex flex-col items-center text-center group transition-all hover:border-[#137fec] relative overflow-hidden">
            <div class="w-14 h-14 bg-slate-50 dark:bg-[#192633] rounded-2xl flex items-center justify-center mb-4 border border-slate-100 dark:border-[#324d67] shadow-inner group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl" style="color: {{ $val['color'] }}">{{ $val['icon'] }}</span>
            </div>
            <span class="text-[9px] font-black uppercase text-slate-400 dark:text-[#92adc9] tracking-[0.2em] mb-1 italic">{{ $val['label'] }}</span>
            <span class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter italic">{{ rtrim(rtrim($statusCounts[$key] ?? 0, '0'), '.') }}</span>
            <div class="absolute -right-2 -bottom-2 opacity-[0.03] group-hover:opacity-10 transition-opacity" style="color: {{ $val['color'] }}">
                <span class="material-symbols-outlined text-7xl">{{ $val['icon'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Active Task Ledger -->
    <div class="bg-white dark:bg-[#111a22] rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden">
        <div class="p-8 border-b border-slate-50 dark:border-[#324d67] flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-800 dark:text-white text-lg tracking-widest uppercase italic mb-1">Antrean Tugas Produksi</h3>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.3em] italic">Memprioritaskan item berdasarkan ambang batas ETD.</p>
            </div>
            <div class="h-12 w-12 bg-slate-50 dark:bg-[#233648] rounded-2xl flex items-center justify-center text-[#137fec] border border-slate-100 dark:border-[#324d67] shadow-inner">
                <span class="material-symbols-outlined">dataset_linked</span>
            </div>
        </div>
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-[#192633]/50 border-b border-slate-100 dark:border-[#324d67]">
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">ID TXN & Entitas</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic text-center">Protokol Status</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic text-right">Target E.T.D</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-[#324d67]">
                    @forelse($queue as $trx)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 bg-slate-100 dark:bg-[#192633] rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-[#137fec] group-hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-xl italic uppercase font-black tracking-tighter">inventory</span>
                                </div>
                                <div>
                                    <span class="font-black text-slate-800 dark:text-white text-sm tracking-widest italic uppercase">#{{ $trx->kode_transaksi }}</span>
                                    <p class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mt-0.5">{{ $trx->pelanggan->nama }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @php
                                $statusMap = [
                                    'proses' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-500', 'border' => 'border-blue-500/20'],
                                    'cuci' => ['bg' => 'bg-cyan-500/10', 'text' => 'text-cyan-500', 'border' => 'border-cyan-500/20'],
                                    'setrika' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                                    'packing' => ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-500', 'border' => 'border-purple-500/20'],
                                ];
                                $s = $statusMap[$trx->status] ?? ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-500', 'border' => 'border-slate-500/20'];
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest italic border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            @php $isOverdue = $trx->tanggal_estimasi->isPast() && $trx->status != 'selesai'; @endphp
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-black italic tracking-tighter {{ $isOverdue ? 'text-red-500 animate-pulse' : 'text-[#137fec]' }}">
                                    {{ $trx->tanggal_estimasi->diffForHumans() }}
                                </span>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5 italic">{{ $trx->tanggal_estimasi->format('d/m/Y H:i') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-8 py-24 text-center text-slate-400 italic text-[10px] uppercase font-bold tracking-[0.4em]">Antrean Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Cards) -->
        <div class="sm:hidden divide-y divide-slate-100 dark:divide-[#324d67]">
            @forelse($queue as $trx)
            <div class="p-6 space-y-4 active:bg-slate-50 dark:active:bg-white/[0.02] transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-slate-100 dark:bg-[#192633] rounded-xl flex items-center justify-center text-slate-400 shadow-inner">
                            <span class="material-symbols-outlined text-xl italic uppercase font-black tracking-tighter">inventory</span>
                        </div>
                        <div>
                            <span class="font-black text-slate-800 dark:text-white text-[11px] tracking-widest italic uppercase">#{{ $trx->kode_transaksi }}</span>
                            <p class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mt-0.5 truncate max-w-[120px]">{{ $trx->pelanggan->nama }}</p>
                        </div>
                    </div>
                    @php
                        $statusMap = [
                            'proses' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-500', 'border' => 'border-blue-500/20'],
                            'cuci' => ['bg' => 'bg-cyan-500/10', 'text' => 'text-cyan-500', 'border' => 'border-cyan-500/20'],
                            'setrika' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-500', 'border' => 'border-amber-500/20'],
                            'packing' => ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-500', 'border' => 'border-purple-500/20'],
                        ];
                        $s = $statusMap[$trx->status] ?? ['bg' => 'bg-slate-500/10', 'text' => 'text-slate-500', 'border' => 'border-slate-500/20'];
                    @endphp
                    <span class="px-2.5 py-1 rounded-lg text-[7px] font-black uppercase tracking-widest italic border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                        {{ $trx->status }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center bg-slate-50 dark:bg-[#192633] px-4 py-3 rounded-2xl border border-slate-100 dark:border-[#324d67]">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Deadline Operasional</span>
                    @php $isOverdue = $trx->tanggal_estimasi->isPast() && $trx->status != 'selesai'; @endphp
                    <div class="text-right">
                        <p class="text-[10px] font-black italic tracking-tighter {{ $isOverdue ? 'text-red-500' : 'text-[#137fec]' }}">
                            {{ $trx->tanggal_estimasi->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic leading-relaxed">Terminal Antrean Clear:<br>Istirahat Jika Diperlukan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
