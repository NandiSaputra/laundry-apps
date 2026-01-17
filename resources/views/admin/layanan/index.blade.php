@extends('layouts.admin')

@section('title', 'Daftar Layanan | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Daftar Layanan')

@section('header-actions')
<a href="{{ route('admin.layanan.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-[#137fec] text-white rounded-lg text-sm font-bold hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
    <span class="material-symbols-outlined text-xl">add_box</span>
    <span>Tambah Layanan</span>
</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Master Data Layanan</h2>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium">Kelola daftar item, harga, dan estimasi pengerjaan.</p>
        </div>
        <div class="relative w-full md:w-80 group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9] group-focus-within:text-[#137fec] transition-colors">search</span>
            <input type="text" id="layananSearch" placeholder="Cari nama layanan atau kode..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all shadow-sm">
        </div>
    </div>

    <!-- Layanan Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($layanans as $layanan)
        <div class="layanan-card bg-white dark:bg-[#111a22] p-5 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] dark:hover:border-[#137fec] transition-all group relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 right-0 p-4">
                <span class="text-[8px] font-bold text-[#137fec] bg-[#137fec]/10 px-2 py-1 rounded border border-[#137fec]/10">#{{ $layanan->kode_layanan }}</span>
            </div>

            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 rounded-lg bg-slate-50 dark:bg-[#192633] border border-slate-100 dark:border-[#324d67] flex items-center justify-center text-slate-400 group-hover:bg-[#137fec] group-hover:text-white transition-all">
                        <span class="material-symbols-outlined text-xl">inventory_2</span>
                    </div>
                    <div>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] text-[8px] font-bold uppercase rounded tracking-widest">{{ $layanan->kategori->nama }}</span>
                        <h4 class="font-bold text-slate-800 dark:text-white mt-0.5 truncate uppercase tracking-tight">{{ $layanan->nama }}</h4>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="bg-slate-50 dark:bg-[#192633] p-2.5 rounded-lg border border-slate-100 dark:border-[#324d67]">
                        <p class="text-[8px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest">Tarif Layanan</p>
                        <p class="text-xs font-black text-[#137fec] mt-0.5 tracking-tighter italic">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</p>
                        <p class="text-[8px] text-slate-400 font-medium uppercase tracking-tighter">Per {{ $layanan->satuan }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-[#192633] p-2.5 rounded-lg border border-slate-100 dark:border-[#324d67]">
                        <p class="text-[8px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest">Estimasi Selesai (ETD)</p>
                        <p class="text-xs font-black text-slate-700 dark:text-white mt-0.5 tracking-tighter italic">{{ $layanan->estimasi_jam }} Jam</p>
                        <p class="text-[8px] text-slate-400 font-medium uppercase tracking-tighter tracking-widest">ESTIMASI</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-50 dark:border-[#324d67] flex items-center justify-between">
                <div>
                    @if($layanan->is_active)
                    <div class="flex items-center text-green-500">
                        <div class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1.5 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                        <span class="text-[8px] font-bold uppercase italic">Aktif</span>
                    </div>
                    @else
                    <div class="flex items-center text-red-500">
                        <div class="h-1.5 w-1.5 rounded-full bg-red-500 mr-1.5"></div>
                        <span class="text-[8px] font-bold uppercase italic">Non-Aktif</span>
                    </div>
                    @endif
                </div>
                <div class="flex items-center gap-1">
                    <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="p-2 text-slate-400 hover:text-[#137fec] hover:bg-[#137fec]/10 rounded-lg transition-all" title="Edit Layanan">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                    </a>
                    <button onclick="confirmDelete('{{ $layanan->id }}', '{{ addslashes($layanan->nama) }}')" 
                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Delete Layanan">
                        <span class="material-symbols-outlined text-lg">delete_sweep</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-[#111a22] rounded-xl border-2 border-dashed border-slate-200 dark:border-[#324d67] flex flex-col items-center justify-center text-slate-400">
            <span class="material-symbols-outlined text-6xl opacity-20">inventory</span>
            <p class="text-[10px] font-bold uppercase tracking-[0.3em] mt-4">Terminal Inventori: Tidak Ada Layanan Terdaftar</p>
        </div>
        @endforelse
    </div>

    @if($layanans->hasPages())
    <div class="mt-6">
        {{ $layanans->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'HAPUS DATA',
            text: `Yakin ingin menghapus layanan ${nama}? Tindakan ini akan menghapus item dari menu transaksi.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            background: document.documentElement.classList.contains('dark') ? '#111a22' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
            customClass: {
                popup: 'rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-2xl',
                confirmButton: 'rounded-lg px-6 py-2.5 font-bold uppercase tracking-widest text-[10px]',
                cancelButton: 'rounded-lg px-6 py-2.5 font-bold uppercase tracking-widest text-[10px]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/layanan/${id}`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                showLoader();
                form.submit();
            }
        });
    }

    // Live Search
    document.getElementById('layananSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.layanan-card').forEach(card => {
            const content = card.textContent.toLowerCase();
            card.style.display = content.includes(term) ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection
