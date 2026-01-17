<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Owner Dashboard | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; background-color: #f6f7f8; }
        .dark body { background-color: #101922; }
        [x-cloak] { display: none !important; }

        :root {
            --primary: #137fec;
            --background-light: #f6f7f8;
            --background-dark: #101922;
        }
        
        .rounded-3xl, .rounded-2xl { border-radius: 0.75rem !important; }
        .rounded-xl { border-radius: 0.5rem !important; }
        .rounded-lg { border-radius: 0.35rem !important; }
        
        .shadow-lg, .shadow-xl, .card-shadow { 
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important; 
        }
        
        thead th {
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #64748b !important;
        }
        .dark thead th { color: #94a3b8 !important; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#101922] text-slate-900 dark:text-slate-100 font-display transition-colors duration-200">
    <div id="global-loader" style="display: none;" class="fixed inset-0 z-9999 place-items-center bg-white/70 dark:bg-slate-900/70 transition-all duration-300 opacity-0 pointer-events-none">
        <div class="flex flex-col items-center">
            <div class="h-10 w-10 border-4 border-slate-200 border-t-[#137fec] rounded-full animate-spin"></div>
            <p class="mt-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Memproses Portal...</p>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: window.innerWidth > 1024 }">
        <!-- Sidebar Backdrop (Mobile Only) -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
        </div>

        <!-- Sidebar Navigation (Unified) -->
        @include('utility.admin.sidebar')

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <header class="flex items-center justify-between border-b border-slate-200 dark:border-[#233648] px-4 sm:px-8 py-3 sm:py-4 bg-white dark:bg-[#111a22] sticky top-0 z-40 transition-colors duration-200">
                <div class="flex items-center gap-3 sm:gap-6">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-[#233648] rounded-lg transition-colors active:scale-95">
                        <span class="material-symbols-outlined text-2xl">menu</span>
                    </button>
                    <div class="flex flex-col">
                        <h2 class="text-sm sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white truncate max-w-[120px] sm:max-w-none">@yield('page-title', 'Analitik')</h2>
                        <p class="text-[7px] sm:text-[8px] font-black text-[#137fec] uppercase tracking-[0.2em] mt-0.5 leading-none">OWNER PORTAL</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    @yield('header-actions')

                    <div x-data="{ userMenu: false }" class="relative">
                        <button @click="userMenu = !userMenu" class="flex items-center gap-2 p-1 sm:p-1.5 rounded-xl hover:bg-slate-50 dark:hover:bg-[#233648] transition-all active:scale-95 group">
                            <div class="h-7 w-7 sm:h-9 sm:w-9 rounded-lg bg-[#137fec] text-white flex items-center justify-center font-bold text-xs uppercase shadow-md shadow-[#137fec]/20">
                                {{ substr(Auth::user()->nama ?? 'O', 0, 1) }}
                            </div>
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="text-xs font-bold text-slate-800 dark:text-white leading-none mb-1 group-hover:text-[#137fec] transition-colors">{{ Auth::user()->nama }}</span>
                                <span class="text-[8px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest leading-none">Master Owner</span>
                            </div>
                        </button>
                        <div x-show="userMenu" @click.away="userMenu = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] rounded-xl shadow-2xl z-50 overflow-hidden" x-cloak>
                            <div class="px-4 py-3 border-b border-slate-50 dark:border-[#233648] sm:hidden">
                                <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->nama }}</p>
                                <p class="text-[10px] text-slate-400 uppercase font-black truncate">Owner Master</p>
                            </div>
                            <a href="{{ route('owner.profile') }}" class="flex items-center px-4 py-2.5 text-xs sm:text-sm text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]">Profil Master</a>
                            <div class="border-t border-slate-100 dark:border-[#233648]"></div>
                            <button onclick="showLogoutModal()" class="w-full text-left flex items-center px-4 py-2.5 text-xs sm:text-sm text-red-600 hover:bg-slate-50 dark:hover:bg-[#233648] font-bold">Keluar Protokol</button>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-8">
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-800 dark:text-green-400 rounded-xl text-sm font-medium flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined mr-2">check_circle</span>
                        {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-500 opacity-50 hover:opacity-100">&times;</button>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Redesigned Logout Modal -->
    <div id="logout-modal" style="display: none;" class="hidden fixed inset-0 z-1000 items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="hideLogoutModal()"></div>
        <div class="relative z-10 w-full max-w-sm bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#233648] rounded-2xl shadow-2xl p-6">
            <div class="bg-red-50 dark:bg-red-500/10 h-14 w-14 rounded-xl flex items-center justify-center text-red-600 mb-4">
                <span class="material-symbols-outlined text-3xl">logout</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Konfirmasi Keluar?</h3>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mb-6">Pilih "Keluar" di bawah ini jika Anda siap untuk mengakhiri sesi dashboard Anda saat ini.</p>
            <div class="flex gap-3">
                <button onclick="hideLogoutModal()" class="flex-1 py-2.5 text-sm font-bold text-slate-500 bg-slate-100 dark:bg-[#233648] hover:bg-slate-200 dark:hover:bg-[#2d445b] rounded-lg transition-colors">Batal</button>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-[#137fec] text-white text-sm font-bold rounded-lg hover:bg-[#137fec]/90 transition-colors">Keluar</button>
                </form>
            </div>
        </div>
    </div>

    @stack('modals')
    @stack('scripts')
    <script>
        function showLogoutModal() { document.getElementById('logout-modal').classList.remove('hidden'); document.getElementById('logout-modal').style.display = 'flex'; }
        function hideLogoutModal() { document.getElementById('logout-modal').classList.add('hidden'); document.getElementById('logout-modal').style.display = 'none'; }
        function showLoader() { const loader = document.getElementById('global-loader'); if(loader) { loader.style.display = 'grid'; loader.classList.remove('hidden'); setTimeout(() => { loader.classList.add('opacity-100'); }, 10); } }
        function hideLoader() { const loader = document.getElementById('global-loader'); if(loader) { loader.classList.remove('opacity-100'); setTimeout(() => { loader.classList.add('hidden'); loader.style.display = 'none'; }, 200); } }
        
        document.querySelectorAll('a').forEach(link => { link.addEventListener('click', function(e) { const h = this.getAttribute('href'); if (h && !h.startsWith('#') && !h.startsWith('javascript:') && this.getAttribute('target') !== '_blank' && !this.hasAttribute('data-no-loader')) showLoader(); }); });
        document.querySelectorAll('form').forEach(form => { form.addEventListener('submit', function(e) { if (!this.hasAttribute('data-no-loader')) showLoader(); }); });
        window.addEventListener('pageshow', () => hideLoader());
        document.addEventListener('DOMContentLoaded', () => { hideLoader(); hideLogoutModal(); });
    </script>
</body>
</html>
