@extends('layouts.admin')

@section('title', 'Tambah Pelanggan | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Tambah Pelanggan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.pelanggan') }}" class="p-2 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-slate-400 hover:text-[#137fec] transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Registrasi Member Baru</h1>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1">Daftarkan pelanggan baru untuk mempermudah transaksi.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden transition-colors">
        <form id="pelangganForm" action="{{ route('admin.pelanggan.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label for="nama" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan nama pelanggan..." 
                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('nama') ring-2 ring-red-500 @enderror"
                       value="{{ old('nama') }}" required>
                @error('nama')
                    <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1.5">
                    <label for="telepon" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Nomor WhatsApp/Telepon</label>
                    <input type="text" name="telepon" id="telepon" placeholder="08xxxxxxxxxx" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('telepon') ring-2 ring-red-500 @enderror"
                           value="{{ old('telepon') }}" required>
                    @error('telepon')
                        <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Email (Opsional)</label>
                    <input type="email" name="email" id="email" placeholder="contoh@gmail.com" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('email') ring-2 ring-red-500 @enderror"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="alamat" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Alamat Lengkap (Opsional)</label>
                <textarea name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat pelanggan..." 
                          class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium">{{ old('alamat') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-[#324d67]">
                <button type="submit" id="submitBtn" class="w-full py-4 bg-[#137fec] text-white rounded-lg font-bold hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined">save</span>
                    <span>Simpan Pelanggan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('pelangganForm');
        const submitBtn = document.getElementById('submitBtn');
        const required = form.querySelectorAll('[required]');
        const check = () => {
            const allFilled = Array.from(required).every(input => input.value.trim());
            submitBtn.disabled = !allFilled;
        };
        form.addEventListener('input', check);
        check();
    });
</script>
@endpush
