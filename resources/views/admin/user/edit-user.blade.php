@extends('layouts.admin')

@section('title', 'Edit User | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.user') }}" class="p-2 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-slate-400 hover:text-[#137fec] transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Perbarui Akun</h1>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1">Ubah data profil, role, atau ganti password user.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden transition-colors">
        <form id="userForm" action="{{ route('admin.user.update', $user->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-1.5">
                <label for="nama" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" 
                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('nama') ring-2 ring-red-500 @enderror"
                       value="{{ old('nama', $user->nama) }}" required>
                @error('nama')
                    <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Email</label>
                    <input type="email" name="email" id="email" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('email') ring-2 ring-red-500 @enderror"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="space-y-1.5">
                    <label for="telepon" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">No. Telepon</label>
                    <input type="text" name="telepon" id="telepon" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium"
                           value="{{ old('telepon', $user->telepon) }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="role" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Hak Akses (Role)</label>
                    <select name="role" id="role" 
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('role') ring-2 ring-red-500 @enderror" required>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="produksi" {{ old('role', $user->role) == 'produksi' ? 'selected' : '' }}>Staf Produksi (Cuci/Setrika)</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Ganti Password (Opsional)</label>
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak diubah" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('password') ring-2 ring-red-500 @enderror">
                    @error('password')
                        <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Status Akun</label>
                <div class="flex items-center gap-4 py-2 px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="is_active" value="1" class="accent-[#137fec]" {{ old('is_active', $user->is_active) == '1' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 group-hover:text-[#137fec] transition-colors uppercase tracking-wider">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="is_active" value="0" class="accent-red-500" {{ old('is_active', $user->is_active) == '0' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 group-hover:text-red-500 transition-colors uppercase tracking-wider">Non-Aktif</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-[#324d67]">
                <button type="submit" id="submitBtn" class="w-full py-4 bg-[#137fec] text-white rounded-lg font-bold hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                    <span class="material-symbols-outlined">save</span>
                    <span>Update Data Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
