@php
    $role = auth()->user()->role;
    $layout = $role == 'owner' ? 'layouts.owner' : ($role == 'admin' ? 'layouts.admin' : 'layouts.kasir');
@endphp

@extends($layout)

@section('title', 'Profil Utama | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Profil Operator')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">Manajemen Identitas</h1>
            <p class="text-[10px] text-slate-500 dark:text-[#92adc9] mt-2 font-black uppercase tracking-[0.3em] italic">Kelola kredensial institusi dan izin keamanan.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111a22] rounded-3xl sm:rounded-[3rem] border border-slate-200 dark:border-[#324d67] shadow-2xl overflow-hidden transition-colors relative">
        <!-- Profile Aesthetic Header -->
        <div class="h-40 sm:h-48 bg-gradient-to-r from-[#137fec] to-[#0d6efd] relative overflow-hidden group">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-from)_0%,_transparent_70%)] from-white"></div>
            </div>
            
            <div class="absolute -bottom-10 sm:-bottom-12 left-6 sm:left-12">
                <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-2xl sm:rounded-3xl bg-white dark:bg-[#111a22] p-1.5 sm:p-2 shadow-2xl border-4 border-white dark:border-[#111a22] group-hover:rotate-6 transition-transform duration-500">
                    <div class="h-full w-full rounded-xl sm:rounded-2xl bg-[#137fec]/10 flex items-center justify-center text-[#137fec] font-black text-3xl sm:text-4xl uppercase italic tracking-tighter shadow-inner">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}{{ strtoupper(substr($user->nama, -1)) }}
                    </div>
                </div>
            </div>
            
            <div class="absolute bottom-4 sm:bottom-6 right-6 sm:right-12 flex items-center gap-3 sm:gap-4">
                <div class="flex flex-col items-end">
                    <span class="text-white font-black uppercase tracking-[0.3em] text-[8px] sm:text-[10px] opacity-70 italic">Jabatan</span>
                    <span class="text-white font-black text-sm sm:text-lg italic tracking-tighter">{{ strtoupper($user->role) }}</span>
                </div>
                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-white/20 backdrop-blur-xl rounded-xl sm:rounded-2xl flex items-center justify-center border border-white/30 text-white">
                    <span class="material-symbols-outlined text-xl sm:text-2xl">verified_user</span>
                </div>
            </div>
        </div>

        <div class="pt-16 sm:pt-20 p-6 sm:p-12">
            <form id="profileForm" action="{{ route(auth()->user()->role . '.profile.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label for="nama" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Nama Personel Institusi</label>
                        <input type="text" name="nama" id="nama" 
                               class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-tighter shadow-inner @error('nama') ring-2 ring-red-500 @enderror"
                               value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')
                            <p class="text-[8px] text-red-500 mt-2 ml-1 font-black uppercase tracking-widest italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="telepon" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Protokol Komunikasi (Telepon)</label>
                        <input type="text" name="telepon" id="telepon" 
                               class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-tighter shadow-inner"
                               value="{{ old('telepon', $user->telepon) }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Handle Otentikasi Sistem (Email)</label>
                    <input type="email" name="email" id="email" 
                           class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-tighter shadow-inner @error('email') ring-2 ring-red-500 @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <p class="text-[8px] text-red-500 mt-2 ml-1 font-black uppercase tracking-widest italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-8 border-t border-slate-50 dark:border-[#324d67] mt-8">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-10 w-10 bg-red-500/10 text-red-500 rounded-xl flex items-center justify-center border border-red-500/20 shadow-inner">
                            <span class="material-symbols-outlined">key</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-[0.2em] italic">Pembaruan Izin Keamanan</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Modifikasi protokol kriptografi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="password" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Matriks Akses Baru (Password)</label>
                            <input type="password" name="password" id="password" 
                                   placeholder="SANDI AMAN"
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-widest shadow-inner @error('password') ring-2 ring-red-500 @enderror">
                            @error('password')
                                <p class="text-[8px] text-red-500 mt-2 ml-1 font-black uppercase tracking-widest italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Verifikasi Ulang Matriks</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   placeholder="VALIDASI SANDI"
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-widest shadow-inner">
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex flex-col items-center">
                    <button type="submit" id="submitBtn" class="hidden w-full py-5 bg-[#137fec] text-white rounded-3xl font-black hover:bg-[#137fec]/90 shadow-2xl shadow-[#137fec]/40 transition-all flex items-center justify-center gap-4 uppercase tracking-[0.3em] text-[10px] italic group">
                        <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">encrypted</span>
                        <span>Perbarui Protokol Identitas</span>
                    </button>
                    <div id="noChangeHint" class="flex flex-col items-center gap-3 opacity-40">
                        <div class="h-1 w-12 bg-slate-200 dark:bg-[#324d67] rounded-full"></div>
                        <p class="text-center text-[9px] text-slate-400 font-black uppercase tracking-[0.4em] italic">Sistem Standby: Tidak Ada Modifikasi Terdeteksi</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ids = ['nama', 'email', 'telepon', 'password', 'password_confirmation'];
        const initial = {}; ids.forEach(id => initial[id] = document.getElementById(id).value);
        const check = () => {
            const changed = ids.some(id => document.getElementById(id).value !== initial[id]);
            const submitBtn = document.getElementById('submitBtn');
            const hint = document.getElementById('noChangeHint');
            
            if(changed) {
                if(submitBtn.classList.contains('hidden')) {
                    submitBtn.classList.remove('hidden');
                    submitBtn.style.opacity = '0';
                    submitBtn.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        submitBtn.style.opacity = '1';
                        submitBtn.style.transform = 'translateY(0)';
                        submitBtn.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    }, 10);
                }
                hint.classList.add('hidden');
            } else {
                submitBtn.classList.add('hidden');
                hint.classList.remove('hidden');
            }
        };
        ids.forEach(id => document.getElementById(id).addEventListener('input', check));
    });
</script>
@endpush
