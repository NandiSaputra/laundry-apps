@php
    $routePrefix = request()->is('owner/*') ? 'owner' : 'admin';
@endphp

@extends("layouts.$routePrefix")

@section('title', 'Konfigurasi Sistem | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Pengaturan Global')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">Identitas Institusi</h1>
            <p class="text-[9px] sm:text-[10px] text-slate-500 dark:text-[#92adc9] mt-2 font-black uppercase tracking-[0.3em] italic">Kelola branding institusi dan protokol operasional.</p>
        </div>
    </div>

    <form id="settingsForm" action="{{ route($routePrefix . '.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Branding & Assets -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Logo Protocol -->
                <div class="bg-white dark:bg-[#111a22] rounded-3xl border border-slate-200 dark:border-[#324d67] shadow-xl p-8 flex flex-col items-center relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-2 h-full bg-[#137fec]"></div>
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-[#92adc9] uppercase tracking-[0.2em] mb-8 w-full italic">Logo Institusi</h3>
                    
                    <div class="relative group">
                        <div class="h-40 w-40 rounded-[2rem] bg-slate-50 dark:bg-[#233648] border-2 border-dashed border-slate-200 dark:border-[#324d67] flex items-center justify-center overflow-hidden transition-all group-hover:border-[#137fec] shadow-inner">
                            @if(isset($settings['shop_logo']))
                                <img id="logoPreview" src="{{ asset('storage/' . $settings['shop_logo']) }}" alt="Logo" class="h-full w-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div id="logoPlaceholder" class="flex flex-col items-center text-slate-300 dark:text-[#5a7690]">
                                    <span class="material-symbols-outlined text-5xl">image</span>
                                    <span class="text-[9px] uppercase font-black tracking-widest mt-2">Data Kosong</span>
                                </div>
                                <img id="logoPreview" src="" alt="Preview" class="hidden h-full w-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                            @endif
                        </div>
                        <label for="shop_logo" class="absolute -bottom-3 -right-3 h-12 w-12 bg-[#137fec] text-white rounded-2xl shadow-2xl cursor-pointer hover:bg-[#137fec]/90 transition-all flex items-center justify-center border-4 border-white dark:border-[#111a22]">
                            <span class="material-symbols-outlined text-2xl">photo_camera</span>
                        </label>
                        <input type="file" name="shop_logo" id="shop_logo" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    
                    <div class="mt-8 p-4 bg-slate-50 dark:bg-[#233648] rounded-2xl border border-slate-100 dark:border-[#324d67] w-full text-center">
                        <p class="text-[8px] text-slate-400 font-black uppercase tracking-[0.2em] italic leading-relaxed">Persyaratan Sistem:<br>PNG/JPG, MAKS 2.0MB DATA</p>
                    </div>
                </div>

                <!-- Strategic Intelligence -->
                <div class="bg-gradient-to-br from-[#137fec] to-[#0d6efd] rounded-[2rem] p-8 text-white shadow-2xl shadow-[#137fec]/30 relative overflow-hidden group">
                    <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform duration-700">
                        <span class="material-symbols-outlined text-[10rem]">description</span>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-10 w-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">verified</span>
                            </div>
                            <h3 class="font-black uppercase tracking-widest text-sm">Peringatan Protokol</h3>
                        </div>
                        <p class="text-[11px] font-medium text-white/90 leading-relaxed italic">
                            Semua data institusi yang dikonfigurasi di sini akan merambat ke struk terminal transaksi dan ekspor analitik di seluruh sistem.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Configuration Modules -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Module: Base Identity -->
                <div class="bg-white dark:bg-[#111a22] rounded-[2.5rem] border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden transition-colors">
                    <div class="p-8 border-b border-slate-50 dark:border-[#324d67] bg-slate-50/30 dark:bg-[#192633]/30 flex items-center gap-4">
                        <div class="h-10 w-10 bg-[#137fec]/10 text-[#137fec] rounded-xl flex items-center justify-center border border-[#137fec]/20">
                            <span class="material-symbols-outlined">badge</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-[0.2em] italic">Metadata Entitas Inti</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Dataset identifikasi institusi utama</p>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label for="shop_name" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Nama Institusi</label>
                            <input type="text" name="shop_name" id="shop_name" 
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-tighter shadow-inner"
                                   value="{{ old('shop_name', $settings['shop_name'] ?? '') }}" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="shop_phone" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Pusat Komunikasi (WhatsApp)</label>
                                <input type="text" name="shop_phone" id="shop_phone" 
                                       class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-tighter shadow-inner"
                                       value="{{ old('shop_phone', $settings['shop_phone'] ?? '') }}" required>
                            </div>

                            <div class="space-y-2">
                                <label for="shop_email" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Gerbang Jaringan (Email)</label>
                                <input type="email" name="shop_email" id="shop_email" 
                                       class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-tighter shadow-inner"
                                       value="{{ old('shop_email', $settings['shop_email'] ?? '') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="shop_hours_weekday" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Jam Operasional (Senin - Sabtu)</label>
                                <input type="text" name="shop_hours_weekday" id="shop_hours_weekday" 
                                       placeholder="Contoh: 08:00 - 20:00"
                                       class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-tighter shadow-inner"
                                       value="{{ old('shop_hours_weekday', $settings['shop_hours_weekday'] ?? '') }}">
                            </div>

                            <div class="space-y-2">
                                <label for="shop_hours_weekend" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Jam Operasional (Minggu)</label>
                                <input type="text" name="shop_hours_weekend" id="shop_hours_weekend" 
                                       placeholder="Contoh: 09:00 - 15:00"
                                       class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black italic tracking-tighter shadow-inner"
                                       value="{{ old('shop_hours_weekend', $settings['shop_hours_weekend'] ?? '') }}">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="shop_address" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Koordinat Geospasial (Alamat)</label>
                            <textarea name="shop_address" id="shop_address" rows="3" 
                                      class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-widest shadow-inner resize-none tracking-tighter" required>{{ old('shop_address', $settings['shop_address'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Module: Terminal Interface -->
                <div class="bg-white dark:bg-[#111a22] rounded-[2.5rem] border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden transition-colors">
                    <div class="p-8 border-b border-slate-50 dark:border-[#324d67] bg-slate-50/30 dark:bg-[#192633]/30 flex items-center gap-4">
                        <div class="h-10 w-10 bg-purple-500/10 text-purple-500 rounded-xl flex items-center justify-center border border-purple-500/20">
                            <span class="material-symbols-outlined">receipt_long</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-[0.2em] italic">Aset Protokol Terminal</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Kustomisasi struk & footer dokumentasi</p>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label for="receipt_footer" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Tanda Tangan Footer Dokumen</label>
                            <textarea name="receipt_footer" id="receipt_footer" rows="3" 
                                      placeholder="SYARAT DAN KETENTUAN MENGENAI PENGAMBILAN BARANG..."
                                      class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-widest shadow-inner resize-none text-[10px]">{{ old('receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>
                            <p class="text-[8px] text-slate-400 dark:text-[#5a7690] mt-3 ml-1 italic font-black uppercase tracking-widest">Injeksi Terminal Global: Ditambahkan ke semua catatan termal.</p>
                        </div>
                    </div>
                </div>

                <!-- Module: Web Presence -->
                <div class="bg-white dark:bg-[#111a22] rounded-[2.5rem] border border-slate-200 dark:border-[#324d67] shadow-xl overflow-hidden transition-colors">
                    <div class="p-8 border-b border-slate-50 dark:border-[#324d67] bg-slate-50/30 dark:bg-[#192633]/30 flex items-center gap-4">
                        <div class="h-10 w-10 bg-green-500/10 text-green-500 rounded-xl flex items-center justify-center border border-green-500/20">
                            <span class="material-symbols-outlined">public</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 dark:text-white text-xs uppercase tracking-[0.2em] italic">Penyebaran Gerbang Klien</h3>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Konfigurasi hero halaman utama</p>
                        </div>
                    </div>
                    
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label for="landing_hero_title" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Proposisi Platform (Judul Hero)</label>
                            <input type="text" name="landing_hero_title" id="landing_hero_title" 
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-tighter shadow-inner"
                                   value="{{ old('landing_hero_title', $settings['landing_hero_title'] ?? '') }}">
                        </div>

                        <div class="space-y-2">
                            <label for="landing_hero_description" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Pernyataan Misi Institusi</label>
                            <textarea name="landing_hero_description" id="landing_hero_description" rows="2" 
                                      class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-widest shadow-inner resize-none text-[10px]">{{ old('landing_hero_description', $settings['landing_hero_description'] ?? '') }}</textarea>
                        </div>

                        <div class="h-px bg-slate-100 dark:bg-[#324d67] my-4"></div>

                        <div class="space-y-2">
                            <label for="landing_feature_title" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Judul Seksi Layanan</label>
                            <input type="text" name="landing_feature_title" id="landing_feature_title" 
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-tighter shadow-inner"
                                   value="{{ old('landing_feature_title', $settings['landing_feature_title'] ?? '') }}">
                        </div>

                        <div class="space-y-2">
                            <label for="landing_feature_description" class="text-[9px] font-black text-slate-400 dark:text-[#92adc9] ml-1 uppercase tracking-[0.3em]">Deskripsi Seksi Layanan</label>
                            <textarea name="landing_feature_description" id="landing_feature_description" rows="2" 
                                      class="w-full px-6 py-4 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-2xl focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-black uppercase italic tracking-widest shadow-inner resize-none text-[10px]">{{ old('landing_feature_description', $settings['landing_feature_description'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end sticky bottom-8 z-20">
                    <button type="submit" id="submitBtn" class="hidden px-10 py-5 bg-[#137fec] text-white rounded-2xl font-black hover:bg-[#137fec]/90 shadow-2xl shadow-[#137fec]/40 transition-all flex items-center gap-4 group uppercase tracking-[0.3em] text-[10px] italic">
                        <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">cloud_upload</span>
                        Sinkronkan Konfigurasi Sistem
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('logoPreview');
        const placeholder = document.getElementById('logoPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
                showSubmit();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function showSubmit() { 
        const btn = document.getElementById('submitBtn');
        if(btn.classList.contains('hidden')) {
            btn.classList.remove('hidden');
            btn.classList.add('animate-bounce-short');
            setTimeout(() => btn.classList.remove('animate-bounce-short'), 500);
        }
    }
    document.addEventListener('DOMContentLoaded', () => {
        ['shop_name', 'shop_phone', 'shop_email', 'shop_address', 'receipt_footer', 'landing_hero_title', 'landing_hero_description', 'shop_hours_weekday', 'shop_hours_weekend', 'landing_feature_title', 'landing_feature_description']
        .forEach(id => {
            const el = document.getElementById(id);
            if(el) el.addEventListener('input', showSubmit);
        });
    });
</script>
<style>
    @keyframes bounce-short {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-short {
        animation: bounce-short 0.5s ease-in-out;
    }
</style>
@endpush
