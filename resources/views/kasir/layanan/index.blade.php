@extends('layouts.kasir')

@section('title', 'Price List Terminal | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Daftar Harga Layanan')

@section('content')
<div class="space-y-6">
    <!-- Search & Sync Info -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96 group">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 group-focus-within:text-[#137fec] transition-colors">
                <span class="material-symbols-outlined text-xl">search</span>
            </span>
            <input type="text" id="priceSearch" placeholder="Cari Layanan/Kategori..." 
                class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#137fec] outline-none transition-all dark:text-white">
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-[#233648] rounded-lg border border-slate-200 dark:border-[#324d67]">
            <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
            <span class="text-[10px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest">Pricing Console Sync: OK</span>
        </div>
    </div>

    <!-- Pricing Terminal Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($layanans as $l)
        <div class="price-card bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] dark:hover:border-[#137fec] transition-all group relative overflow-hidden flex flex-col h-full">
            <div class="flex items-start justify-between mb-4">
                <div class="h-10 w-10 bg-slate-50 dark:bg-[#233648] rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-[#137fec] group-hover:text-white transition-all border border-slate-100 dark:border-[#324d67]">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
                <span class="text-[9px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest italic mt-1">{{ $l->kategori->nama }}</span>
            </div>
            
            <div class="flex-1">
                <h4 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-tight leading-tight mb-2">{{ $l->nama }}</h4>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic">Terminal Tariff ID: {{ $l->kode_layanan }}</p>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-50 dark:border-[#324d67] flex items-end justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 italic">Base Rate</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-bold text-[#137fec] tracking-tighter">Rp {{ number_format($l->harga, 0, ',', '.') }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">/{{ $l->satuan }}</span>
                    </div>
                </div>
                <div class="px-2 py-0.5 bg-green-50 dark:bg-green-500/10 rounded-md border border-green-100 dark:border-green-500/20 text-green-600 dark:text-green-400 text-[8px] font-bold uppercase tracking-widest">
                    ACTIVE
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    document.getElementById('priceSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.price-card').forEach(card => {
            const content = card.textContent.toLowerCase();
            card.style.display = content.includes(term) ? 'flex' : 'none';
        });
    });
</script>
@endsection
