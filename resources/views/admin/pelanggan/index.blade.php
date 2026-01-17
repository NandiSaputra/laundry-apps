@extends(auth()->user()->role == 'admin' ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Database Pelanggan | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Database Pelanggan')

@section('header-actions')
<button onclick="openAddModal()" class="flex items-center gap-2 px-4 py-2.5 bg-[#137fec] text-white rounded-lg text-sm font-bold hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
    <span class="material-symbols-outlined text-xl">person_add</span>
    <span>Tambah Pelanggan</span>
</button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Management Pelanggan</h2>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium">Data member dan riwayat loyalitas pelanggan.</p>
        </div>
        <div class="relative w-full md:w-80 group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9] group-focus-within:text-[#137fec] transition-colors">search</span>
            <input type="text" id="memberSearch" placeholder="Cari nama atau telepon..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all shadow-sm">
        </div>
    </div>

    <!-- Customer Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($pelanggans as $p)
        <div class="customer-card bg-white dark:bg-[#111a22] p-5 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] dark:hover:border-[#137fec] transition-all group relative overflow-hidden flex flex-col justify-between">
            <div class="absolute top-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition-all">
                <span class="text-[9px] font-bold text-[#137fec] bg-[#137fec]/10 px-2 py-1 rounded">#{{ $p->kode_pelanggan }}</span>
            </div>

            <div>
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-12 w-12 rounded-full bg-[#137fec]/10 border border-[#137fec]/20 flex items-center justify-center text-[#137fec] font-bold text-sm uppercase tracking-tighter">
                        {{ substr($p->nama, 0, 2) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <h4 class="font-bold text-slate-800 dark:text-white truncate uppercase tracking-tight">{{ $p->nama }}</h4>
                        <div class="flex items-center gap-1.5 text-slate-500 dark:text-[#92adc9] text-[11px] font-medium italic">
                            <span class="material-symbols-outlined text-[14px]">call</span>
                            {{ $p->telepon }}
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-2.5 text-slate-400 dark:text-[#5a7690]">
                        <span class="material-symbols-outlined text-[16px] mt-0.5">location_on</span>
                        <p class="text-[10px] font-medium leading-relaxed italic line-clamp-2 uppercase">{{ $p->alamat ?? 'ALAMAT BELUM DIREKAM' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-4">
                        <div class="bg-slate-50 dark:bg-[#192633] p-2 rounded-lg border border-slate-100 dark:border-[#324d67]">
                            <p class="text-[9px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest">TOTAL PESANAN</p>
                            <p class="text-xs font-black text-slate-800 dark:text-white mt-0.5 tracking-tighter italic">{{ $p->total_transaksi ?? 0 }}x</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#192633] p-2 rounded-lg border border-slate-100 dark:border-[#324d67]">
                            <p class="text-[9px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest">NILAI LOYALITAS</p>
                            <p class="text-xs font-black text-[#137fec] mt-0.5 tracking-tighter italic text-right">Rp {{ number_format($p->total_belanja ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-50 dark:border-[#324d67] flex items-center justify-between">
                <span class="text-[9px] font-bold text-slate-300 dark:text-[#5a7690] uppercase italic">Last seen: {{ $p->updated_at->diffForHumans() }}</span>
                <div class="flex items-center gap-1">
                    <button onclick="openEditModal('{{ $p->id }}', '{{ addslashes($p->nama) }}', '{{ $p->telepon }}', '{{ addslashes($p->alamat) }}')" 
                            class="p-2 text-slate-400 hover:text-[#137fec] hover:bg-[#137fec]/10 rounded-lg transition-all" title="Edit Data">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                    </button>
                    @if(auth()->user()->role == 'admin')
                    <button onclick="confirmDelete('{{ $p->id }}', '{{ addslashes($p->nama) }}')" 
                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Delete Permanent">
                        <span class="material-symbols-outlined text-lg">delete_sweep</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-[#111a22] rounded-xl border-2 border-dashed border-slate-200 dark:border-[#324d67] flex flex-col items-center justify-center text-slate-400">
            <span class="material-symbols-outlined text-6xl opacity-20">person_off</span>
            <p class="text-[10px] font-bold uppercase tracking-[0.3em] mt-4">Database: Tidak Ada Data Ditemukan</p>
        </div>
        @endforelse
    </div>

    @if($pelanggans->hasPages())
    <div class="mt-6">
        {{ $pelanggans->links() }}
    </div>
    @endif
</div>

<!-- Add/Edit Modal -->
<div id="memberModal" class="hidden fixed inset-0 z-100 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] rounded-2xl shadow-2xl overflow-hidden transition-colors">
        <form id="memberForm" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-[#137fec] uppercase tracking-tight">Tambah Pelanggan</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Terminal Registrasi | Entri Data Profil</p>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-red-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Nama Lengkap Member</label>
                    <input type="text" name="nama" id="formNama" required 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase placeholder:text-slate-300" placeholder="MASUKKAN NAMA...">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Nomor Kontak (Telepon/WA)</label>
                    <input type="text" name="telepon" id="formTelepon" required 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all tracking-wider" placeholder="08XXXXXXXXXX">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Alamat Domisili</label>
                    <textarea name="alamat" id="formAlamat" rows="3" 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase" placeholder="ALAMAT LENGKAP..."></textarea>
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
        document.getElementById('modalTitle').textContent = 'Tambah Pelanggan Baru';
        document.getElementById('memberForm').action = "{{ route(auth()->user()->role . '.pelanggan.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formNama').value = '';
        document.getElementById('formTelepon').value = '';
        document.getElementById('formAlamat').value = '';
        
        const modal = document.getElementById('memberModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(id, nama, telepon, alamat) {
        document.getElementById('modalTitle').textContent = 'Update Profile Pelanggan';
        document.getElementById('memberForm').action = `{{ url(auth()->user()->role . '/pelanggan') }}/${id}`;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formNama').value = nama;
        document.getElementById('formTelepon').value = telepon;
        document.getElementById('formAlamat').value = alamat;
        
        const modal = document.getElementById('memberModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('memberModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'HAPUS DATA',
            text: `Yakin ingin menghapus data member ${nama}? Data transaksi historis tetap tersimpan tapi nama ini akan hilang dari database aktif.`,
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
                form.action = `{{ url(auth()->user()->role . '/pelanggan') }}/${id}`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                showLoader();
                form.submit();
            }
        });
    }

    // Live Search Logic
    document.getElementById('memberSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.customer-card').forEach(card => {
            const content = card.textContent.toLowerCase();
            card.style.display = content.includes(term) ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection
