@extends('layouts.admin')

@section('title', 'Kategori Layanan | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Kategori Layanan')

@section('header-actions')
<button onclick="openAddModal()" class="flex items-center gap-2 px-4 py-2.5 bg-[#137fec] text-white rounded-lg text-sm font-bold hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
    <span class="material-symbols-outlined text-xl">category</span>
    <span>Tambah Kategori</span>
</button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Management Kategori</h2>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium">Pengelompokan jenis layanan laundry Anda.</p>
        </div>
        <div class="relative w-full md:w-80 group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9] group-focus-within:text-[#137fec] transition-colors">search</span>
            <input type="text" id="kategoriSearch" placeholder="Cari kategori..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all shadow-sm">
        </div>
    </div>

    <!-- Kategori Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($categories as $category)
        <div class="kategori-card bg-white dark:bg-[#111a22] p-5 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] dark:hover:border-[#137fec] transition-all group relative overflow-hidden flex flex-col justify-between">
            <div class="flex items-start justify-between mb-4">
                <div class="h-12 w-12 rounded-xl bg-[#137fec]/10 border border-[#137fec]/20 flex items-center justify-center text-[#137fec] group-hover:bg-[#137fec] group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">grid_view</span>
                </div>
                <div class="flex flex-col items-end">
                    @if($category->is_active)
                    <span class="px-2 py-0.5 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[8px] font-bold uppercase tracking-widest rounded border border-green-100 dark:border-green-500/20">AKTIF</span>
                    @else
                    <span class="px-2 py-0.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[8px] font-bold uppercase tracking-widest rounded border border-red-100 dark:border-red-500/20">NON-AKTIF</span>
                    @endif
                </div>
            </div>

            <div class="space-y-1 mb-4">
                <h4 class="font-bold text-slate-800 dark:text-white uppercase tracking-tight">{{ $category->nama }}</h4>
                <p class="text-[10px] text-slate-500 dark:text-[#92adc9] font-medium leading-relaxed italic line-clamp-2">{{ $category->deskripsi ?? 'TIDAK ADA DESKRIPSI LAYANAN' }}</p>
            </div>

            <div class="pt-4 border-t border-slate-50 dark:border-[#324d67] flex items-center justify-between">
                <span class="text-[9px] font-bold text-slate-300 dark:text-[#5a7690] uppercase italic">Registri Sistem</span>
                <div class="flex items-center gap-1">
                    <button onclick="openEditModal('{{ $category->id }}', '{{ addslashes($category->nama) }}', '{{ addslashes($category->deskripsi) }}', '{{ $category->is_active }}')" 
                            class="p-2 text-slate-400 hover:text-[#137fec] hover:bg-[#137fec]/10 rounded-lg transition-all" title="Edit Kategori">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                    </button>
                    <button onclick="confirmDelete('{{ $category->id }}', '{{ addslashes($category->nama) }}')" 
                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Hapus Kategori">
                        <span class="material-symbols-outlined text-lg">delete_sweep</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-[#111a22] rounded-xl border-2 border-dashed border-slate-200 dark:border-[#324d67] flex flex-col items-center justify-center text-slate-400">
            <span class="material-symbols-outlined text-6xl opacity-20">inventory_2</span>
            <p class="text-[10px] font-bold uppercase tracking-[0.3em] mt-4">Pusat Data: Tidak Ada Kategori Ditemukan</p>
        </div>
        @endforelse
    </div>

    @if($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<!-- Add/Edit Modal -->
<div id="kategoriModal" class="hidden fixed inset-0 z-100 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] rounded-2xl shadow-2xl overflow-hidden transition-colors">
        <form id="kategoriForm" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-[#137fec] uppercase tracking-tight">Entri Kategori</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Data Master Terminal | Kategori</p>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-red-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Nama Kategori</label>
                    <input type="text" name="nama" id="formNama" required 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase placeholder:text-slate-300" placeholder="MASUKKAN NAMA KATEGORI...">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Deskripsi Kategori</label>
                    <textarea name="deskripsi" id="formDeskripsi" rows="3" 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase" placeholder="PENJELASAN SINGKAT..."></textarea>
                </div>
                <div class="flex items-center gap-2 py-2">
                    <input type="checkbox" name="is_active" id="formIsActive" value="1" checked class="w-4 h-4 text-[#137fec] border-slate-300 rounded focus:ring-[#137fec]">
                    <label for="formIsActive" class="text-[10px] font-bold text-slate-600 dark:text-[#92adc9] uppercase tracking-widest italic">Kategori Aktif & Muncul di Kasir</label>
                </div>
            </div>
            
            <button type="submit" class="w-full mt-8 py-4 bg-[#137fec] text-white rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all">
                Simpan Data
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Kategori Baru';
        document.getElementById('kategoriForm').action = "{{ route('admin.kategori.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formNama').value = '';
        document.getElementById('formDeskripsi').value = '';
        document.getElementById('formIsActive').checked = true;
        
        const modal = document.getElementById('kategoriModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(id, nama, deskripsi, isActive) {
        document.getElementById('modalTitle').textContent = 'Update Master Kategori';
        document.getElementById('kategoriForm').action = `/admin/kategori/${id}`;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formNama').value = nama;
        document.getElementById('formDeskripsi').value = deskripsi == 'null' ? '' : deskripsi;
        document.getElementById('formIsActive').checked = isActive == '1';
        
        const modal = document.getElementById('kategoriModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('kategoriModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'HAPUS DATA',
            text: `Yakin ingin menghapus kategori ${nama}? Seluruh layanan di bawah kategori ini akan terpengaruh.`,
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
                form.action = `/admin/kategori/${id}`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                showLoader();
                form.submit();
            }
        });
    }

    // Live Search
    document.getElementById('kategoriSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.kategori-card').forEach(card => {
            const content = card.textContent.toLowerCase();
            card.style.display = content.includes(term) ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection
