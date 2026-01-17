<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['shop_name'] ?? 'LaundryApp' }} - Jasa Laundry Terbaik, Bersih & Wangi</title>
    <meta name="description" content="{{ $settings['landing_hero_description'] ?? 'Jasa laundry kiloan dan satuan terbaik dengan layanan antar jemput. Proses cepat, bersih, wangi, dan bisa ditracking secara real-time.' }}">
    <meta name="keywords" content="laundry, cuci kiloan, cuci satuan, dry clean, laundry terdekat, laundry express, {{ $settings['shop_name'] ?? 'LaundryApp' }}">
    <meta name="author" content="{{ $settings['shop_name'] ?? 'LaundryApp' }}">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $settings['shop_name'] ?? 'LaundryApp' }} - Jasa Laundry Terbaik">
    <meta property="og:description" content="{{ $settings['landing_hero_description'] ?? 'Solusi laundry modern dengan tracking real-time. Bersih, wangi, dan tepat waktu.' }}">
    @if(isset($settings['shop_logo']))
    <meta property="og:image" content="{{ asset('storage/' . $settings['shop_logo']) }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="{{ $settings['shop_name'] ?? 'LaundryApp' }} - Jasa Laundry Terbaik">
    <meta property="twitter:description" content="{{ $settings['landing_hero_description'] ?? 'Solusi laundry modern dengan tracking real-time. Bersih, wangi, dan tepat waktu.' }}">
    @if(isset($settings['shop_logo']))
    <meta property="twitter:image" content="{{ asset('storage/' . $settings['shop_logo']) }}">
    @endif

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "LocalBusiness",
      "name": "{{ $settings['shop_name'] ?? 'LaundryApp' }}",
      "image": "{{ isset($settings['shop_logo']) ? asset('storage/' . $settings['shop_logo']) : '' }}",
      "description": "{{ $settings['landing_hero_description'] ?? 'Jasa laundry profesional' }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ $settings['shop_address'] ?? 'Alamat Outlet' }}",
        "addressCountry": "ID"
      },
      "telephone": "{{ $settings['shop_phone'] ?? '' }}",
      "url": "{{ url('/') }}"
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="hero-pattern min-h-screen scroll-smooth">
    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 glass border-b border-slate-200" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <div class="h-12 flex items-center justify-center">
                        @if(isset($settings['shop_logo']))
                            <img src="{{ asset('storage/' . $settings['shop_logo']) }}" alt="Logo" class="h-10 w-auto object-contain max-w-[150px]">
                        @else
                            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                                <i class="ph-bold ph-sketch-logo text-white text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-lg sm:text-xl font-black tracking-tighter text-slate-900 block leading-none">{{ $settings['shop_name'] ?? 'LaundryApp' }}</span>
                        <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest italic">Professional Care</span>
                    </div>
                </div>
                
                {{-- Desktop Links --}}
                <div class="hidden md:flex items-center gap-10">
                    <a href="#how-it-works" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Cara Kerja</a>
                    <a href="#features" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">Layanan</a>
                    <a href="#faq" class="text-sm font-bold text-slate-600 hover:text-blue-600 transition-colors">FAQ</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-slate-900 text-white px-8 py-3 rounded-2xl text-sm font-black italic uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">Dashboard</a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-xl transition-all active:scale-95">
                    <i class="ph-bold text-2xl" :class="mobileMenu ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Links --}}
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-b border-slate-100 p-4 space-y-2 shadow-2xl"
             x-cloak>
            <a href="#how-it-works" @click="mobileMenu = false" class="block px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all">Cara Kerja</a>
            <a href="#features" @click="mobileMenu = false" class="block px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all">Layanan</a>
            <a href="#faq" @click="mobileMenu = false" class="block px-4 py-3 rounded-xl text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition-all">FAQ</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-4 py-4 rounded-xl text-sm font-black text-center bg-blue-600 text-white uppercase italic tracking-widest shadow-lg shadow-blue-200">Dashboard Utama</a>
            @endauth
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-24 overflow-hidden">
        {{-- Floating background elements --}}
        <div class="absolute top-20 left-10 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest italic mb-6 border border-blue-100">
                    <i class="ph-bold ph-sparkle"></i>
                    Solusi Laundry Modern & Terpercaya
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter mb-8 leading-[0.9]">
                    {!! $settings['landing_hero_title'] ?? 'Laundry <span class="text-blue-600">Terpercaya</span><br>Bersih & Wangi.' !!}
                </h1>
                <p class="text-xl text-slate-500 mb-12 leading-relaxed font-medium">
                    {{ $settings['landing_hero_description'] ?? 'Lupakan tumpukan cucian. Kami hadir dengan sistem tracking real-time dan penanganan profesional untuk hasil yang maksimal.' }}
                </p>

                {{-- Tracking Form --}}
                <div id="tracking" class="max-w-xl mx-auto bg-white p-3 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-slate-100 flex flex-col md:flex-row items-center gap-3">
                    <form action="{{ route('public.track') }}" method="GET" class="flex w-full gap-3">
                        <div class="flex-1 flex items-center px-6 gap-4 py-2">
                            <i class="ph-bold ph-magnifying-glass text-slate-400 text-2xl"></i>
                            <input type="text" name="kode" required placeholder="Cek status pakai kode transaksi..." class="w-full py-3 focus:outline-none text-slate-700 font-bold text-lg placeholder:text-slate-300">
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-10 py-5 rounded-[2rem] font-black uppercase italic tracking-widest text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 active:scale-95">
                            Track Pesanan
                        </button>
                    </form>
                </div>

                @if(session('error'))
                <div class="mt-8 bg-red-50 text-red-600 px-6 py-4 rounded-2xl font-bold flex items-center justify-center gap-3 border border-red-100 max-w-sm mx-auto animate-bounce">
                    <i class="ph-bold ph-warning-circle text-xl"></i>
                    {{ session('error') }}
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- How it Works --}}
    <section id="how-it-works" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] italic mb-4">Proses Kami</h2>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Cara Kerja Layanan Kami</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                {{-- Connectivity line --}}
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-px bg-slate-100 -translate-y-1/2 z-0"></div>

                {{-- Step 1 --}}
                <div class="relative z-10 text-center space-y-6 group">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto border-4 border-white shadow-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <i class="ph-bold ph-package text-4xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900 italic mb-2 tracking-tight">1. Antar Toko</h4>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed px-4">Bawa cucian kotor Anda ke outlet kami. Tim kami akan mendata dan menimbang dengan teliti.</p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative z-10 text-center space-y-6 group">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto border-4 border-white shadow-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <i class="ph-bold ph-fire text-4xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900 italic mb-2 tracking-tight">2. Proses Profesional</h4>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed px-4">Pencucian menggunakan deterjen premium dan mesin modern. Pantau prosesnya lewat fitur Tracking.</p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative z-10 text-center space-y-6 group">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto border-4 border-white shadow-xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <i class="ph-bold ph-hand-heart text-4xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900 italic mb-2 tracking-tight">3. Siap Diambil</h4>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed px-4">Anda akan menerima notifikasi. Cucian sudah bersih, wangi, rapi, dan siap dibawa pulang.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features / Services --}}
    <section id="features" class="py-24 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-16">
                <div class="max-w-xl">
                    <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] italic mb-4">Pilihan Layanan</h2>
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter leading-none">
                        {{ $settings['landing_feature_title'] ?? 'Layanan Unggulan Kami' }}
                    </h3>
                </div>
                <p class="text-slate-500 font-medium max-w-sm">{{ $settings['landing_feature_description'] ?? 'Tersedia berbagai paket laundry yang dirancang khusus untuk memenuhi gaya hidup Anda yang sibuk.' }}</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($layanans as $layanan)
                <div class="group p-10 bg-white rounded-[3rem] border border-slate-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500 relative overflow-hidden">
                    {{-- Hover blob --}}
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>

                    <div class="relative">
                        <div class="flex justify-between items-start mb-10">
                            <div class="bg-slate-50 p-4 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white border border-slate-100 transition-all duration-300">
                                <i class="ph-bold ph-sketch-logo text-3xl"></i>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">{{ $layanan->kategori->nama ?? 'Laundry' }}</span>
                                <div class="text-2xl font-black text-slate-900 italic tracking-tighter leading-none">
                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-widest mt-1">per {{ $layanan->satuan }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <h4 class="text-xl font-black text-slate-900 mb-4 italic tracking-tight uppercase leading-tight">{{ $layanan->nama }}</h4>
                        <p class="text-slate-500 text-sm font-medium mb-10 leading-relaxed">{{ $layanan->deskripsi ?? 'Hasil bersih maksimal, wangi tahan lama, dan rapi setiap saat.' }}</p>
                        
                        <div class="flex items-center gap-6 pt-6 border-t border-slate-50">
                            <span class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                <i class="ph-bold ph-clock-countdown text-blue-500 text-lg"></i>
                                {{ $layanan->estimasi_jam }} Jam
                            </span>
                            <span class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                                <i class="ph-bold ph-shield-check text-green-500 text-lg"></i>
                                Berkualitas
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Key Benefits / Keunggulan --}}
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-slate-50 z-0 hidden lg:block"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-1/2 space-y-12">
                    <div>
                        <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] italic mb-4">Mengapa Kami?</h2>
                        <h3 class="text-5xl font-black text-slate-900 tracking-tighter leading-[0.9] mb-6">Penanganan Profesional Untuk Pakaian Anda</h3>
                        <p class="text-lg text-slate-500 font-medium">Kami mengerti bahwa setiap pakaian memiliki nilai tersendiri bagi Anda. Itulah mengapa kami mengutamakan kualitas di setiap helai benangnya.</p>
                    </div>

                    <div class="space-y-8">
                        <div class="flex gap-6 items-start group">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-100 flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="ph-bold ph-timer text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 italic tracking-tight mb-1 uppercase">Pengerjaan Ekspres</h4>
                                <p class="text-slate-500 text-sm font-medium leading-relaxed">Selesai tepat waktu sesuai estimasi. Bahkan tersedia paket kilat 6-12 jam untuk kebutuhan mendesak.</p>
                            </div>
                        </div>

                        <div class="flex gap-6 items-start group">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-100 flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="ph-bold ph-fingerprint text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 italic tracking-tight mb-1 uppercase">Tracking Real-Time</h4>
                                <p class="text-slate-500 text-sm font-medium leading-relaxed">Pantau status cucian Anda dari mana saja melalui smartphone. Tidak perlu lagi bertanya-tanya kapan selesai.</p>
                            </div>
                        </div>

                        <div class="flex gap-6 items-start group">
                            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 border border-blue-100 flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="ph-bold ph-selection-all text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-slate-900 italic tracking-tight mb-1 uppercase">Deterjen Ramah Serat</h4>
                                <p class="text-slate-500 text-sm font-medium leading-relaxed">Kami hanya menggunakan deterjen dan pewangi premium yang aman untuk kulit dan tidak merusak serat kain.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2 grid grid-cols-2 gap-6 relative">
                    {{-- Stats cards visually represented --}}
                    <div class="bg-indigo-600 p-8 rounded-[3rem] text-white flex flex-col justify-end h-72 shadow-2xl shadow-indigo-200 mt-12">
                        <div class="text-5xl font-black mb-2 tracking-tighter leading-none italic">10k+</div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-80 italic">Happy Clients</p>
                    </div>
                    <div class="bg-white p-8 rounded-[3rem] text-slate-900 border border-slate-100 flex flex-col justify-end h-72 shadow-2xl shadow-slate-100">
                        <div class="text-5xl font-black text-blue-600 mb-2 tracking-tighter leading-none italic">99%</div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 italic">Cleaning Accuracy</p>
                    </div>
                    <div class="bg-slate-900 p-8 rounded-[3rem] text-white flex flex-col justify-end h-72 shadow-2xl shadow-slate-400 -mt-12 col-span-2">
                        <div class="flex justify-between items-end">
                            <div>
                                <div class="text-5xl font-black mb-2 tracking-tighter leading-none italic">Fast</div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] opacity-80 italic">Turnaround Time</p>
                            </div>
                            <div class="text-blue-500">
                                <i class="ph-bold ph-lightning text-6xl opacity-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section id="faq" class="py-24 bg-slate-50 border-y border-slate-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] italic mb-4">Pertanyaan Umum</h2>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">Sering Ditanyakan</h3>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div x-data="{ open: false }" class="bg-white rounded-3xl border border-slate-100 overflow-hidden transition-all">
                    <button @click="open = !open" class="w-full px-8 py-6 flex justify-between items-center text-left">
                        <span class="font-black text-slate-900 italic tracking-tight uppercase">Berapa lama waktu pengerjaan normal?</span>
                        <i :class="open ? 'ph-bold ph-minus' : 'ph-bold ph-plus'" class="text-blue-600 transition-all"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-8 pb-6 text-slate-600 text-sm font-medium leading-relaxed">
                        Pengerjaan normal biasanya memakan waktu 2-3 hari kerja tergantung beban cucian. Untuk layanan kilat (Express), cucian bisa selesai dalam waktu 6-24 jam saja.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div x-data="{ open: false }" class="bg-white rounded-3xl border border-slate-100 overflow-hidden transition-all">
                    <button @click="open = !open" class="w-full px-8 py-6 flex justify-between items-center text-left">
                        <span class="font-black text-slate-900 italic tracking-tight uppercase">Bagaimana cara cek status cucian saya?</span>
                        <i :class="open ? 'ph-bold ph-minus' : 'ph-bold ph-plus'" class="text-blue-600 transition-all"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-8 pb-6 text-slate-600 text-sm font-medium leading-relaxed">
                        Gunakan kode transaksi yang tertera pada nota/label Anda, masukkan pada kolom "Track Pesanan" di bagian atas halaman ini untuk melihat status proses secara real-time.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div x-data="{ open: false }" class="bg-white rounded-3xl border border-slate-100 overflow-hidden transition-all">
                    <button @click="open = !open" class="w-full px-8 py-6 flex justify-between items-center text-left">
                        <span class="font-black text-slate-900 italic tracking-tight uppercase">Apakah ada garansi jika cucian belum bersih?</span>
                        <i :class="open ? 'ph-bold ph-minus' : 'ph-bold ph-plus'" class="text-blue-600 transition-all"></i>
                    </button>
                    <div x-show="open" x-collapse class="px-8 pb-6 text-slate-600 text-sm font-medium leading-relaxed">
                        Tentu! Kami mengutamakan kepuasan pelanggan. Jika cucian dirasa kurang bersih atau belum wangi, silakan hubungi tim kami dalam 24 jam setelah pengambilan untuk diproses kembali secara gratis.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-[4rem] p-12 md:p-24 text-white overflow-hidden relative shadow-2xl shadow-blue-900/40">
                {{-- Decorative background --}}
                <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 20 0, 50 0, 100 100 Z" fill="url(#grad)"></path>
                        <defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" style="stop-color:rgb(59,130,246);stop-opacity:1" /><stop offset="100%" style="stop-color:rgb(79,70,229);stop-opacity:1" /></linearGradient></defs>
                    </svg>
                </div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 items-center gap-16">
                    <div class="max-w-lg">
                        <h2 class="text-[10px] font-black text-blue-400 uppercase tracking-[0.3em] italic mb-6">Hubungi Kami</h2>
                        <h3 class="text-4xl md:text-6xl font-black mb-8 leading-[0.9] tracking-tighter italic">Ada Pertanyaan? Kami Siap Membantu!</h3>
                        
                        <div class="space-y-8 mb-12">
                            <div class="flex items-start gap-6 border-l-2 border-white/10 pl-6 hover:border-blue-500 transition-all group">
                                <div class="bg-white/5 p-4 rounded-2xl group-hover:bg-blue-600 transition-all h-fit">
                                    <i class="ph ph-map-pin text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-[10px] uppercase tracking-widest opacity-40 mb-2 italic">Outlet Fisik</h4>
                                    <p class="font-bold text-lg leading-snug">{{ $settings['shop_address'] ?? 'Alamat kami segera diupdate.' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-6 border-l-2 border-white/10 pl-6 hover:border-blue-500 transition-all group">
                                <div class="bg-white/5 p-4 rounded-2xl group-hover:bg-green-600 transition-all h-fit">
                                    <i class="ph ph-whatsapp-logo text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-[10px] uppercase tracking-widest opacity-40 mb-2 italic">Layanan Chat</h4>
                                    <p class="font-bold text-lg leading-snug">{{ $settings['shop_phone'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['shop_phone'] ?? '') }}" target="_blank" class="inline-flex items-center gap-4 bg-blue-600 text-white px-12 py-6 rounded-[2rem] font-black uppercase italic tracking-widest text-lg hover:bg-blue-700 transition-all shadow-2xl shadow-blue-500/20 active:scale-95 group">
                            Hubungi Lewat WA
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-2 transition-transform"></i>
                        </a>
                    </div>
                    
                    <div class="hidden lg:block">
                        {{-- High-end visual representation instead of map --}}
                        <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 border border-white/10 rounded-[4rem] p-12 backdrop-blur-sm relative overflow-hidden group">
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <i class="ph ph-clock-countdown text-[120px] mb-8 text-blue-500 opacity-20 group-hover:rotate-12 transition-transform duration-1000"></i>
                                <h4 class="text-3xl font-black italic tracking-tight uppercase leading-none mb-4">Jam Operasional</h4>
                                <div class="space-y-2 opacity-80">
                                    <p class="font-bold italic uppercase tracking-widest text-xs">Senin - Sabtu</p>
                                    <p class="text-xl font-black font-mono">{{ $settings['shop_hours_weekday'] ?? '08:00 - 20:00' }}</p>
                                    <div class="h-px w-12 bg-white/20 mx-auto my-4"></div>
                                    <p class="font-bold italic uppercase tracking-widest text-xs">Minggu</p>
                                    <p class="text-xl font-black font-mono">{{ $settings['shop_hours_weekend'] ?? '09:00 - 15:00' }}</p>
                                </div>
                            </div>
                            {{-- Decorative light --}}
                            <div class="absolute -top-1/2 -left-1/2 w-full h-full bg-blue-500/10 rounded-full blur-3xl group-hover:animate-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center bg-transparent">
            <div class="flex flex-col md:flex-row justify-center items-center gap-8 mb-12">
                <div class="flex items-center gap-4">
                    <div class="h-8 flex items-center justify-center">
                        @if(isset($settings['shop_logo']))
                            <img src="{{ asset('storage/' . $settings['shop_logo']) }}" alt="Logo" class="h-8 w-auto object-contain max-w-[100px]">
                        @else
                            <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center">
                                <i class="ph-bold ph-sketch-logo text-white text-sm"></i>
                            </div>
                        @endif
                    </div>
                    <span class="text-lg font-black tracking-tighter text-slate-900 italic uppercase">{{ $settings['shop_name'] ?? 'LaundryApp' }}</span>
                </div>
                <div class="hidden md:block w-px h-6 bg-slate-200"></div>
                <p class="text-slate-400 font-medium text-sm italic tracking-tight italic">Memberikan standar baru dalam kebersihan pakaian Anda.</p>
            </div>
            <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.4em]">
                &copy; {{ date('Y') }} {{ $settings['shop_name'] ?? 'LaundryApp' }} Professional Care.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/@@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
