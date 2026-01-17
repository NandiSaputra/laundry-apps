@extends('layouts.admin')

@section('title', 'Edit Layanan | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Edit Layanan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.layanan') }}" class="p-2 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-lg text-slate-400 hover:text-[#137fec] transition-all shadow-sm">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Update Service Data</h1>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1 font-medium">Ubah informasi detail atau harga item laundry ini.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#111a22] rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden transition-colors">
        <form id="layananForm" action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kategori -->
                <div class="space-y-2">
                    <label for="kategori_id" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Service Category</label>
                    <select name="kategori_id" id="kategori_id" 
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-bold uppercase tracking-tight @error('kategori_id') border-red-500 @enderror" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('kategori_id', $layanan->kategori_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kode -->
                <div class="space-y-2">
                    <label for="kode_layanan" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Service ID Code</label>
                    <input type="text" name="kode_layanan" id="kode_layanan" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-black tracking-[0.15em] uppercase @error('kode_layanan') border-red-500 @enderror"
                           value="{{ old('kode_layanan', $layanan->kode_layanan) }}" required>
                </div>

                <!-- Nama -->
                <div class="md:col-span-2 space-y-2">
                    <label for="nama" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Service Name / Item Description</label>
                    <input type="text" name="nama" id="nama" 
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-bold uppercase tracking-tight @error('nama') border-red-500 @enderror"
                           value="{{ old('nama', $layanan->nama) }}" required>
                </div>

                <!-- Satuan -->
                <div class="space-y-2">
                    <label for="satuan" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Pricing Unit</label>
                    <select name="satuan" id="satuan" 
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-bold uppercase tracking-widest" required>
                        <option value="kg" {{ old('satuan', $layanan->satuan) == 'kg' ? 'selected' : '' }}>KILOGRAM (KG)</option>
                        <option value="pcs" {{ old('satuan', $layanan->satuan) == 'pcs' ? 'selected' : '' }}>PIECE (PCS)</option>
                        <option value="pasang" {{ old('satuan', $layanan->satuan) == 'pasang' ? 'selected' : '' }}>PASANG (SET)</option>
                        <option value="meter" {{ old('satuan', $layanan->satuan) == 'meter' ? 'selected' : '' }}>METER (M)</option>
                    </select>
                </div>

                <!-- Harga -->
                <div class="space-y-2">
                    <label for="harga" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Unit Rate (IDR)</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#137fec] font-black text-xs uppercase transition-colors">Rp</span>
                        <input type="number" name="harga" id="harga" 
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-black tracking-tighter text-lg"
                               value="{{ old('harga', intval($layanan->harga)) }}" required>
                    </div>
                </div>

                <!-- Estimasi -->
                <div class="space-y-2">
                    <label for="estimasi_jam" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Processing E.T.D</label>
                    <div class="relative group">
                        <input type="number" name="estimasi_jam" id="estimasi_jam" 
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-black"
                               value="{{ old('estimasi_jam', $layanan->estimasi_jam) }}" required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] text-[#137fec] font-black uppercase tracking-widest italic">Hours</span>
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Availability Status</label>
                    <div class="flex items-center gap-6 py-2 px-1">
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="is_active" value="1" class="w-4 h-4 text-[#137fec] focus:ring-[#137fec]/20" {{ old('is_active', $layanan->is_active) == '1' ? 'checked' : '' }}>
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 group-hover:text-[#137fec] transition-colors uppercase tracking-[0.1em] italic">READY / LIVE</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer group">
                            <input type="radio" name="is_active" value="0" class="w-4 h-4 text-red-500 focus:ring-red-500/20" {{ old('is_active', $layanan->is_active) == '0' ? 'checked' : '' }}>
                            <span class="text-[10px] font-black text-slate-600 dark:text-slate-300 group-hover:text-red-500 transition-colors uppercase tracking-[0.1em] italic">OFFLINE / SUSPEND</span>
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2 space-y-2">
                    <label for="deskripsi" class="text-[10px] font-black text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-[0.2em] italic">Execution Notes / Internal Policy</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" 
                              class="w-full px-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl focus:ring-4 focus:ring-[#137fec]/10 outline-none text-slate-800 dark:text-white transition-all font-bold uppercase tracking-tight">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-50 dark:border-[#324d67]">
                <button type="submit" id="submitBtn" class="w-full py-5 bg-[#137fec] text-white rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-[#137fec]/90 shadow-2xl shadow-[#137fec]/30 transition-all flex items-center justify-center gap-3 group">
                    <span class="material-symbols-outlined group-hover:rotate-90 transition-transform">save</span>
                    <span>Synchronize Service Data</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
