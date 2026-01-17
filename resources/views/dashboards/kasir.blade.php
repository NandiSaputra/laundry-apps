@extends('layouts.kasir')

@section('title', 'Kasir Terminal | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Kasir Terminal')

@section('header-actions')
<div class="flex items-center gap-4">
    <div class="text-right hidden sm:block">
        <p class="text-[8px] text-slate-400 dark:text-[#92adc9] font-black uppercase tracking-[0.2em] mb-0.5 italic">Sesi Operasional</p>
        <p class="text-[10px] text-[#137fec] font-black tracking-tight uppercase">{{ Auth::user()->nama }}</p>
    </div>
    <div class="h-10 w-10 bg-[#137fec]/10 border border-[#137fec]/20 rounded-xl flex items-center justify-center text-[#137fec]">
        <span class="material-symbols-outlined">person_pin</span>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Operational Dashboard Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight italic">Terminal POS Aktif</h2>
            <p class="text-[10px] sm:text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium italic">Shift: {{ now()->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-[#111a22] p-5 sm:p-6 rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-sm relative overflow-hidden group">
            <h3 class="text-[8px] sm:text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] mb-2 sm:mb-3 italic">Omzet Hari Ini</h3>
            <p class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tighter">Rp {{ number_format($stats['income_today'] ?? 0, 0, ',', '.') }}</p>
            <div class="absolute -right-2 -bottom-2 opacity-5 text-[#137fec] group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-5xl sm:text-6xl">payments</span>
            </div>
        </div>

        <div class="bg-white dark:bg-[#111a22] p-6 rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-sm relative overflow-hidden group border-l-4 border-l-amber-500">
            <h3 class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] mb-3 italic">Antrean / Pesanan Aktif</h3>
            <div class="flex items-baseline gap-2">
                <p class="text-2xl font-black text-slate-800 dark:text-white tracking-tighter">{{ $stats['pending_orders'] ?? 0 }}</p>
                <span class="text-[9px] font-bold text-amber-500 uppercase italic tracking-widest">Dalam Proses</span>
            </div>
            <div class="absolute -right-2 -bottom-2 opacity-5 text-amber-500 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-6xl">pending_actions</span>
            </div>
        </div>
    </div>

    <!-- Quick Entry Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <h3 class="text-xs font-black text-slate-500 dark:text-[#92adc9] uppercase tracking-[0.3em] flex items-center gap-2">
                <span class="h-1 w-6 bg-[#137fec] rounded-full"></span>
                Tindakan Cepat
            </h3>
            
            <a href="{{ route('kasir.transaksi.baru') }}" class="block p-8 bg-[#137fec] text-white rounded-3xl shadow-2xl shadow-[#137fec]/30 hover:-translate-y-1 transition-all group relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-2xl font-black italic tracking-tighter uppercase leading-tight">Transaksi Baru</h4>
                    <p class="text-[9px] text-white/70 font-bold mt-2 tracking-widest uppercase italic">Inisialisasi Permintaan Pelanggan</p>
                </div>
                <div class="absolute -right-6 -bottom-6 opacity-20 group-hover:scale-125 transition-transform">
                    <span class="material-symbols-outlined text-[120px]">add_circle</span>
                </div>
            </a>

            <div class="bg-white dark:bg-[#111a22] p-6 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 bg-slate-50 dark:bg-[#192633] rounded-xl flex items-center justify-center text-[#137fec]">
                        <span class="material-symbols-outlined">info</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Info Terminal</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-white">POS-ID: {{ strtoupper(Auth::user()->role) }}-{{ Auth::id() }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="p-3 bg-slate-50 dark:bg-[#192633] rounded-xl border border-slate-100 dark:border-[#324d67] flex justify-between items-center text-[10px]">
                        <span class="font-bold text-slate-500 uppercase tracking-widest italic">Waktu Server</span>
                        <span class="font-black text-slate-800 dark:text-white" id="clock">--:--:--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Ledger -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-500 dark:text-[#92adc9] uppercase tracking-[0.3em] flex items-center gap-2">
                    <span class="h-1 w-6 bg-[#137fec] rounded-full"></span>
                    Jurnal Terbaru
                </h3>
                <span class="text-[8px] font-black bg-[#137fec]/10 text-[#137fec] px-3 py-1.5 rounded-full uppercase tracking-widest italic border border-[#137fec]/20">Jejak Audit</span>
            </div>

            <div class="bg-white dark:bg-[#111a22] rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden">
                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-[#192633]/50 border-b border-slate-100 dark:border-[#324d67]">
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Detail TXN</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic text-center">Protokol</th>
                                <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic text-right">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-[#324d67]">
                            @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-all group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 bg-slate-100 dark:bg-[#192633] rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-[#137fec] group-hover:text-white transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-xl">receipt_long</span>
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 dark:text-white text-sm tracking-tighter uppercase italic">#{{ $trx->kode_transaksi }}</p>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $trx->pelanggan->nama }} • {{ $trx->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @php
                                        $statusClass = [
                                            'selesai' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                            'proses' => 'bg-[#137fec]/10 text-[#137fec] border-[#137fec]/20',
                                            'cuci' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                            'packing' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                            'diambil' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                        ][$trx->status] ?? 'bg-amber-500/10 text-amber-500 border-amber-500/20';
                                    @endphp
                                    <span class="text-[8px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest italic border {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $trx->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <p class="font-black text-slate-800 dark:text-white text-sm tracking-tighter italic">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-[0.1em] mt-0.5">Penyelesaian Bersih</p>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-8 py-20 text-center text-slate-400 italic text-[10px] uppercase font-bold tracking-widest">Tidak Ada Sesi Terdeteksi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="sm:hidden divide-y divide-slate-100 dark:divide-[#324d67]">
                    @forelse($recentTransactions as $trx)
                    <div class="p-5 space-y-4 transition-all active:bg-slate-50 dark:active:bg-white/[0.02]">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 bg-[#137fec] text-white rounded-xl flex items-center justify-center shadow-lg shadow-[#137fec]/20">
                                    <span class="material-symbols-outlined text-lg">receipt_long</span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-[#137fec] italic uppercase tracking-widest">#{{ $trx->kode_transaksi }}</p>
                                    <p class="text-sm font-black text-slate-800 dark:text-white uppercase italic tracking-tighter mt-0.5">{{ $trx->pelanggan->nama }}</p>
                                </div>
                            </div>
                            @php
                                $statusClass = [
                                    'selesai' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                    'proses' => 'bg-[#137fec]/10 text-[#137fec] border-[#137fec]/20',
                                    'cuci' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                    'packing' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                    'diambil' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                ][$trx->status] ?? 'bg-amber-500/10 text-amber-500 border-amber-500/20';
                            @endphp
                            <span class="text-[7px] font-black px-2 py-1 rounded-full uppercase tracking-widest italic border {{ $statusClass }}">
                                {{ str_replace('_', ' ', $trx->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-end bg-slate-50 dark:bg-[#192633] p-3 rounded-2xl border border-slate-100 dark:border-[#324d67]">
                            <div>
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mb-1 italic">Waktu Transaksi</p>
                                <p class="text-[10px] font-black text-slate-800 dark:text-white">{{ $trx->created_at->format('d M, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mb-1 italic text-right">Total Nilai</p>
                                <p class="text-xs font-black text-slate-800 dark:text-white italic tracking-tighter">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center py-20">
                        <span class="material-symbols-outlined text-4xl text-slate-200 dark:text-[#324d67] opacity-50">history_edu</span>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-3 italic">Tidak Ada Aktivitas Sesi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const element = document.getElementById('clock');
        if (element) {
            element.textContent = now.toTimeString().split(' ')[0];
        }
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush
@endsection
