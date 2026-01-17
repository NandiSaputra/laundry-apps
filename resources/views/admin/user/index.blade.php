@extends('layouts.admin')

@section('title', 'Manajemen User | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Manajemen User')

@section('header-actions')
<button onclick="openAddModal()" class="flex items-center gap-2 px-4 py-2.5 bg-[#137fec] text-white rounded-lg text-sm font-bold hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
    <span class="material-symbols-outlined text-xl">person_add</span>
    <span>Tambah User</span>
</button>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Pengaturan Akses Staf</h2>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium">Kelola akun dan hak akses administrator sistem.</p>
        </div>
        <div class="relative w-full md:w-80 group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9] group-focus-within:text-[#137fec] transition-colors">search</span>
            <input type="text" id="userSearch" placeholder="Cari nama atau email..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all shadow-sm">
        </div>
    </div>

    <!-- User Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($users as $user)
        <div class="user-card bg-white dark:bg-[#111a22] p-5 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm hover:border-[#137fec] dark:hover:border-[#137fec] transition-all group relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-4 mb-5">
                    <div class="h-12 w-12 rounded-xl bg-[#137fec]/10 border border-[#137fec]/20 flex items-center justify-center text-[#137fec] font-bold text-lg uppercase tracking-widest">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <h4 class="font-bold text-slate-800 dark:text-white truncate uppercase tracking-tight">{{ $user->nama }}</h4>
                        <span class="text-[10px] text-slate-500 dark:text-[#92adc9] font-medium lowercase tracking-tight truncate">{{ $user->email }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest">Hak Akses</span>
                        @if($user->role === 'admin')
                            <span class="px-2 py-0.5 bg-[#137fec]/10 text-[#137fec] rounded text-[8px] font-bold uppercase border border-[#137fec]/10 tracking-widest">ADMIN</span>
                        @elseif($user->role === 'kasir')
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 rounded text-[8px] font-bold uppercase tracking-widest">KASIR</span>
                        @elseif($user->role === 'produksi')
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 rounded text-[8px] font-bold uppercase tracking-widest">PRODUKSI</span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 rounded text-[8px] font-bold uppercase tracking-widest">OWNER</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest">Status</span>
                        @if($user->is_active)
                            <div class="flex items-center text-green-500">
                                <div class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1.5 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                                <span class="text-[8px] font-bold uppercase italic">Aktif</span>
                            </div>
                        @else
                            <div class="flex items-center text-slate-400">
                                <div class="h-1.5 w-1.5 rounded-full bg-slate-300 mr-1.5"></div>
                                <span class="text-[8px] font-bold uppercase italic">Ditangguhkan</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-slate-50 dark:border-[#324d67] flex items-center justify-between">
                <span class="text-[9px] font-bold text-slate-300 dark:text-[#5a7690] uppercase italic">Dibuat: {{ $user->created_at->format('M Y') }}</span>
                <div class="flex items-center gap-1">
                    @if(auth()->user()->id !== $user->id)
                    <button onclick="openEditModal('{{ $user->id }}', '{{ addslashes($user->nama) }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->is_active }}')" 
                            class="p-2 text-slate-400 hover:text-[#137fec] hover:bg-[#137fec]/10 rounded-lg transition-all" title="Edit Profile">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                    </button>
                    <button onclick="confirmDelete('{{ $user->id }}', '{{ addslashes($user->nama) }}')" 
                            class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-all" title="Hapus User">
                        <span class="material-symbols-outlined text-lg">person_remove</span>
                    </button>
                    @else
                    <span class="text-[9px] font-bold text-[#137fec] uppercase italic px-2">Ini Anda</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-[#111a22] rounded-xl border-2 border-dashed border-slate-200 dark:border-[#324d67] flex flex-col items-center justify-center text-slate-400">
            <span class="material-symbols-outlined text-6xl opacity-20">group_off</span>
            <p class="text-[10px] font-bold uppercase tracking-[0.3em] mt-4">Registri Keamanan: Tidak Ada User Tambahan</p>
        </div>
        @endforelse
    </div>

    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Add/Edit Modal -->
<div id="userModal" class="hidden fixed inset-0 z-100 items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative z-10 w-full max-w-lg bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] rounded-2xl shadow-2xl overflow-hidden transition-colors">
        <form id="userForm" method="POST" class="p-8">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-[#137fec] uppercase tracking-tight">Pendaftaran Akses</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Terminal Keamanan | Kredensial User</p>
                </div>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-red-600 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Nama Lengkap Staf</label>
                        <input type="text" name="nama" id="formNama" required 
                            class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase" placeholder="NAME...">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Role Akses</label>
                        <select name="role" id="formRole" required class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase">
                            <option value="admin">ADMIN</option>
                            <option value="kasir">KASIR</option>
                            <option value="produksi">PRODUKSI</option>
                            <option value="owner">OWNER</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Alamat Email (ID Login)</label>
                    <input type="email" name="email" id="formEmail" required 
                           class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all lowercase" placeholder="staf@email.com">
                </div>

                <div class="space-y-1.5" id="passwordGroup">
                    <label class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest ml-1 italic">Password Akun</label>
                    <input type="password" name="password" id="formPassword" 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all" placeholder="PASSWORD">
                    <p id="passwordNote" class="text-[8px] text-slate-400 italic mt-1 hidden">* Kosongkan password jika tidak ingin mengganti.</p>
                </div>

                <div class="flex items-center gap-2 py-2">
                    <input type="checkbox" name="is_active" id="formIsActive" value="1" checked class="w-4 h-4 text-[#137fec] border-slate-300 rounded focus:ring-[#137fec]">
                    <label for="formIsActive" class="text-[10px] font-bold text-slate-600 dark:text-[#92adc9] uppercase tracking-widest italic">User Aktif & Diijinkan Login</label>
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
        document.getElementById('modalTitle').textContent = 'Daftarkan Akun Akses Baru';
        document.getElementById('userForm').action = "{{ route('admin.user.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('formNama').value = '';
        document.getElementById('formEmail').value = '';
        document.getElementById('formRole').value = 'kasir';
        document.getElementById('formPassword').required = true;
        document.getElementById('formPassword').placeholder = 'MIN. 8 KARAKTER';
        document.getElementById('passwordNote').classList.add('hidden');
        document.getElementById('formIsActive').checked = true;
        
        const modal = document.getElementById('userModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function openEditModal(id, nama, email, role, isActive) {
        document.getElementById('modalTitle').textContent = 'Perbarui Kredensial User';
        document.getElementById('userForm').action = `/admin/user/${id}`;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('formNama').value = nama;
        document.getElementById('formEmail').value = email;
        document.getElementById('formRole').value = role;
        document.getElementById('formPassword').required = false;
        document.getElementById('formPassword').placeholder = 'KOSONGKAN JIKA TIDAK BERUBAH';
        document.getElementById('passwordNote').classList.remove('hidden');
        document.getElementById('formIsActive').checked = isActive == '1';
        
        const modal = document.getElementById('userModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('userModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'HAPUS DATA',
            text: `Yakin ingin menghapus akses user ${nama}? Tindakan ini permanen dan user tidak bisa login lagi.`,
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
                form.action = `/admin/user/${id}`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                showLoader();
                form.submit();
            }
        });
    }

    // Live Search
    document.getElementById('userSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.user-card').forEach(card => {
            const content = card.textContent.toLowerCase();
            card.style.display = content.includes(term) ? 'block' : 'none';
        });
    });
</script>
@endpush
@endsection
