@extends('layouts.admin')

@section('title', 'Tambah Kategori | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.kategori') }}" class="p-2 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-slate-400 hover:text-[#137fec] transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Buat Kategori Baru</h1>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1">Tambahkan jenis layanan laundry baru ke sistem Anda.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden transition-colors">
        <form id="kategoriForm" action="{{ route('admin.kategori.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="space-y-1.5">
                <label for="nama" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Nama Kategori</label>
                <input type="text" name="nama" id="nama" placeholder="Contoh: Cuci Kiloan, Dry Cleaning" 
                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium @error('nama') ring-2 ring-red-500 @enderror"
                       value="{{ old('nama') }}" required>
                @error('nama')
                    <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label for="deskripsi" class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Jelaskan detail kategori ini..." 
                          class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-wider">Status Kategori</label>
                <div class="flex items-center gap-4 py-2 px-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="is_active" value="1" class="accent-[#137fec]" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 group-hover:text-[#137fec] transition-colors uppercase tracking-wider">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="radio" name="is_active" value="0" class="accent-red-500" {{ old('is_active') == '0' ? 'checked' : '' }}>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 group-hover:text-red-500 transition-colors uppercase tracking-wider">Non-Aktif</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-[#324d67]">
                <button type="submit" id="submitBtn" class="w-full py-4 bg-[#137fec] text-white rounded-lg font-bold hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined">save</span>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nameInput = document.getElementById('nama');
        const submitBtn = document.getElementById('submitBtn');
        const check = () => {
            submitBtn.disabled = !nameInput.value.trim();
        };
        nameInput.addEventListener('input', check);
        check();
    });
</script>
@endpush
