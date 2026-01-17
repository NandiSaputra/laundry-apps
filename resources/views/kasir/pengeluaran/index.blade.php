@extends('layouts.kasir')

@section('title', 'Expense Console | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Pengeluaran Kas')

@section('header-actions')
<button onclick="openAddModal()" class="flex items-center gap-2 px-4 py-2 bg-[#137fec] text-white rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
    <span class="material-symbols-outlined text-lg">add_circle</span>
    <span>Input Pengeluaran</span>
</button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex items-center justify-between transition-colors">
        <div class="flex flex-col">
            <span class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-1">Total Expenses (Month)</span>
            <span class="text-xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</span>
        </div>
        <div class="h-10 w-10 bg-red-50 dark:bg-red-500/10 rounded-xl flex items-center justify-center text-red-600 dark:text-red-400">
            <span class="material-symbols-outlined">trending_down</span>
        </div>
    </div>

    <!-- Expense Table -->
    <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden transition-colors">
        <div class="p-6 border-b border-slate-100 dark:border-[#324d67] bg-slate-50/50 dark:bg-[#192633]">
            <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-tight">Recent Logs</h3>
        </div>
        
        <div class="overflow-x-auto text-slate-800 dark:text-slate-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-[#192633] text-[10px] uppercase tracking-wider font-bold text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 text-right">Amount (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-[#324d67]">
                    @forelse($pengeluarans as $p)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-400 text-xs">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-tight">{{ $p->nama }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[9px] font-bold px-2 py-0.5 bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] rounded-lg tracking-widest uppercase">{{ $p->kategori ?? 'UNSORTED' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-red-500 text-sm tracking-tight">{{ number_format($p->jumlah, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <span class="material-symbols-outlined text-5xl opacity-20">history</span>
                                <p class="text-[10px] font-bold uppercase tracking-widest mt-2">No Logs Saved</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengeluarans->hasPages())
        <div class="p-6 border-t border-slate-100 dark:border-[#324d67] bg-slate-50/30 dark:bg-[#192633]">
            {{ $pengeluarans->links() }}
        </div>
        @endif
    </div>
</div>

@push('modals')
<div id="expenseModal" class="hidden fixed inset-0 z-100 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] rounded-2xl shadow-2xl overflow-hidden transition-colors">
        <form action="{{ route('kasir.pengeluaran.store') }}" method="POST" class="p-8">
            @csrf
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-[#137fec] uppercase tracking-tight">Expense Entry</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Terminal Ledger | Outflow</p>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-red-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">What was it for?</label>
                    <input type="text" name="nama" required 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">Amount (IDR)</label>
                        <input type="number" name="jumlah" required 
                            class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">Category</label>
                        <input type="text" name="kategori" 
                            class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all" placeholder="Opsional...">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">Entry Date</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">Internal Memo</label>
                    <textarea name="keterangan" rows="2" 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all" placeholder="Opsional..."></textarea>
                </div>
            </div>
            
            <button type="submit" class="w-full mt-8 py-4 bg-[#137fec] text-white rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all">
                Authorize Entry
            </button>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openAddModal() {
        const modal = document.getElementById('expenseModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        const modal = document.getElementById('expenseModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection
