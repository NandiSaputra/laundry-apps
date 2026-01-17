@php
    $routePrefix = request()->is('owner/*') ? 'owner' : (request()->is('kasir/*') ? 'kasir' : 'admin');
@endphp

@extends("layouts.$routePrefix")

@section('title', 'Manajemen Pengeluaran | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Pengeluaran Kas')

@section('header-actions')
@if($routePrefix !== 'owner')
<button type="button" onclick="openAddModal()" class="flex items-center gap-3 px-6 py-3 bg-[#137fec] text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-[#137fec]/90 transition-all shadow-2xl shadow-[#137fec]/30 group">
    <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">add_task</span>
    <span>Tambah Pengeluaran</span>
</button>
@endif
@endsection

@section('content')
<div class="space-y-8">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
        <div class="bg-white dark:bg-[#111a22] p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl flex items-center gap-6 transition-colors border-l-4 border-l-red-500 relative overflow-hidden group">
            <div class="h-12 w-12 sm:h-14 sm:w-14 bg-red-50 dark:bg-red-500/10 rounded-2xl flex items-center justify-center text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20 shadow-inner group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-2xl sm:text-3xl">trending_down</span>
            </div>
            <div>
                <p class="text-[8px] sm:text-[9px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.3em] leading-none mb-1.5 sm:mb-2 italic">Pengeluaran Bulan Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tighter italic">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-7xl sm:text-8xl">payments</span>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white dark:bg-[#111a22] rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-2xl overflow-hidden transition-colors">
        <div class="p-6 sm:p-8 border-b border-slate-50 dark:border-[#324d67] bg-slate-50/30 dark:bg-[#192633]/30 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h3 class="font-black text-slate-800 dark:text-white text-xs sm:text-sm uppercase tracking-widest italic leading-none">Log Pengeluaran</h3>
                <p class="text-[7px] sm:text-[8px] font-black text-slate-400 uppercase mt-1.5 sm:mt-1 tracking-[0.4em] italic">Catatan pengeluaran operasional yang diaudit</p>
            </div>
            
            <form action="{{ route($routePrefix . '.pengeluaran.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full lg:w-auto">
                <div class="flex items-center bg-white dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl px-3 sm:px-4 py-2 shadow-inner flex-1 sm:flex-none">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-transparent border-none text-[9px] sm:text-[10px] font-black uppercase tracking-widest focus:ring-0 outline-none text-slate-600 dark:text-white transition-all w-full sm:w-32 italic">
                    <span class="text-slate-300 font-black px-2 text-[8px] sm:text-[10px]">KE</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-transparent border-none text-[9px] sm:text-[10px] font-black uppercase tracking-widest focus:ring-0 outline-none text-slate-600 dark:text-white transition-all w-full sm:w-32 italic">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 sm:flex-none h-10 sm:h-12 w-full sm:w-12 bg-[#137fec] text-white rounded-xl shadow-lg shadow-[#137fec]/30 hover:bg-[#137fec]/90 transition-all flex items-center justify-center group uppercase font-black tracking-widest text-[9px] sm:text-base">
                        <span class="material-symbols-outlined group-hover:scale-110 transition-transform sm:block hidden">filter_alt</span>
                        <span class="sm:hidden">Filter Data</span>
                    </button>
                    @if(request()->anyFilled(['start_date', 'end_date']))
                        <a href="{{ route($routePrefix . '.pengeluaran.index') }}" class="h-10 sm:h-12 w-10 sm:w-12 bg-red-500/10 text-red-500 rounded-xl border border-red-500/20 hover:bg-red-500/20 transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined">close</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="hidden lg:block overflow-x-auto text-slate-800 dark:text-slate-200">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white dark:bg-[#192633] border-b border-slate-50 dark:border-[#324d67]">
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Data Temporal</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Entitas Pengeluaran</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Klasifikasi</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Nilai Transaksi</th>
                        <th class="px-8 py-5 text-[9px] font-black text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em] italic">Diaudit Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-[#324d67]">
                    @forelse($pengeluarans as $p)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-[10px] font-black text-slate-400 uppercase italic tracking-widest">{{ $p->tanggal->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight italic">{{ $p->nama }}</span>
                                @if($p->keterangan)
                                    <span class="text-[8px] font-medium text-slate-400 uppercase tracking-widest italic line-clamp-1">{{ $p->keterangan }}</span>
                                @endif
                            </div>
                        </td>
                         <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] rounded-full text-[8px] font-black uppercase tracking-widest italic border border-slate-200 dark:border-[#324d67]">
                                {{ $p->kategori ?? 'TIDAK TERKLASIFIKASI' }}
                            </span>
                        </td>
                        <td class="px-8 py-5 font-black text-red-500 italic tracking-tighter text-sm">
                            Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <div class="h-6 w-6 bg-[#137fec]/10 text-[#137fec] rounded-lg flex items-center justify-center text-[8px] font-black uppercase border border-[#137fec]/20 italic">
                                    {{ strtoupper(substr($p->user->nama ?? '?', 0, 1)) }}
                                </div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ $p->user->nama ?? '-' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-24 text-center text-slate-400 italic text-[10px] uppercase font-bold tracking-widest">Buku Besar Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="lg:hidden divide-y divide-slate-50 dark:divide-[#324d67]">
            @forelse($pengeluarans as $p)
            <div class="p-6 space-y-4 active:bg-slate-50 dark:active:bg-white/[0.02] transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-red-500/10 text-red-500 rounded-xl flex items-center justify-center border border-red-500/20">
                            <span class="material-symbols-outlined">trending_down</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">{{ $p->tanggal->format('d M Y') }}</p>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight italic mt-0.5">{{ $p->nama }}</h4>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 bg-slate-50 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] rounded-lg text-[7px] font-black uppercase tracking-widest italic border border-slate-100 dark:border-[#324d67]">
                        {{ $p->kategori }}
                    </span>
                </div>
                
                <div class="flex justify-between items-end bg-slate-50 dark:bg-[#192633] p-4 rounded-2xl border border-slate-100 dark:border-[#324d67]">
                    <div>
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mb-1 italic">Audit Oleh</p>
                        <div class="flex items-center gap-1.5">
                            <div class="h-5 w-5 bg-[#137fec] text-white rounded flex items-center justify-center text-[8px] font-black uppercase italic">{{ substr($p->user->nama ?? '?', 0, 1) }}</div>
                            <span class="text-[10px] font-black text-slate-800 dark:text-white italic">{{ $p->user->nama ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] text-slate-400 font-bold uppercase tracking-widest mb-1 italic text-right">Nilai Tunai</p>
                        <p class="text-sm font-black text-red-500 italic tracking-tighter">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic">Tidak Ada Log Deteksi</p>
            </div>
            @endforelse
        </div>
        
        @if($pengeluarans->hasPages())
        <div class="p-8 border-t border-slate-50 dark:border-[#324d67] bg-slate-50/20 dark:bg-[#192633]/20">
            {{ $pengeluarans->links() }}
        </div>
        @endif
    </div>
</div>

@push('modals')
<div id="expense-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative z-10 w-full max-w-xl bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-300">
        <div class="p-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 id="modal-title" class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">Catat Pengeluaran</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1 italic">Mendaftarkan protokol pengeluaran modal baru.</p>
                </div>
                <button onclick="closeModal()" class="h-10 w-10 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined text-3xl">close</span>
                </button>
            </div>
            
            <form id="expense-form" action="{{ route($routePrefix . '.pengeluaran.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Identitas Pengeluaran</label>
                        <input type="text" name="nama" id="form-nama" required placeholder="NAMA PENGELUARAN" class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl text-sm font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase italic tracking-tighter shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Buku Besar Klasifikasi</label>
                        <select name="kategori" id="form-kategori" class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl text-[10px] font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase tracking-widest italic shadow-inner">
                            <option value="Operasional">Biaya Operasional</option>
                            <option value="Supplies">Suplai & Bahan Baku</option>
                            <option value="Gaji">Aset Manusia (Gaji)</option>
                            <option value="Utilitas">Utilitas Dasar (Listrik/Air)</option>
                            <option value="Lainnya">Protokol Lain-lain</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Nilai Transaksi (Rp)</label>
                        <input type="number" name="jumlah" id="form-jumlah" required placeholder="0.00" class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl text-lg font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all italic tracking-tight shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Penyelarasan Waktu</label>
                        <input type="date" name="tanggal" id="form-tanggal" required value="{{ date('Y-m-d') }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl text-sm font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all italic shadow-inner">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] ml-1">Catatan Audit (Opsional)</label>
                    <textarea name="keterangan" id="form-keterangan" rows="3" placeholder="Data kontekstual mengenai pengeluaran ini..." class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl text-xs font-black focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase italic tracking-widest shadow-inner resize-none"></textarea>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 py-5 bg-[#137fec] text-white rounded-2xl font-black hover:bg-[#137fec]/90 shadow-2xl shadow-[#137fec]/30 transition-all uppercase tracking-[0.3em] text-[10px] italic">Simpan Data</button>
                    <button type="button" onclick="closeModal()" class="flex-1 py-5 bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] rounded-2xl font-black hover:bg-slate-200 dark:hover:bg-[#2d445b] transition-all uppercase tracking-[0.3em] text-[10px] italic">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush

@push('scripts')
<script>
    function openAddModal() {
        const modal = document.getElementById('expense-modal');
        const form = document.getElementById('expense-form');
        const title = document.getElementById('modal-title');
        title.textContent = 'Catat Pengeluaran';
        form.reset();
        document.getElementById('form-tanggal').value = "{{ date('Y-m-d') }}";
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            modal.querySelector('.relative').classList.add('scale-100', 'opacity-100');
            modal.querySelector('.relative').classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('expense-modal');
        modal.querySelector('.relative').classList.remove('scale-100', 'opacity-100');
        modal.querySelector('.relative').classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>
@endpush
@endsection
