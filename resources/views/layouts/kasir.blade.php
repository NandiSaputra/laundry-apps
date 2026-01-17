<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-bg: #f5f7fa;
            --primary-color: #137fec;
            --sidebar-width: 260px;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--primary-bg); }
        [x-cloak] { display: none !important; }

        /* LaundryBiz Global CSS Overrides */
        .rounded-3xl, .rounded-2xl { border-radius: 0.75rem !important; }
        .rounded-xl { border-radius: 0.5rem !important; }
        .rounded-lg { border-radius: 0.35rem !important; }
        
        /* Force Primary Color */
        .text-indigo-600, .text-indigo-500 { color: var(--primary-color) !important; }
        .bg-indigo-600, .bg-indigo-500 { background-color: var(--primary-color) !important; }
        .focus\:ring-indigo-500:focus { --tw-ring-color: var(--primary-color) !important; }
        .border-indigo-100 { border-color: rgba(19, 127, 236, 0.1) !important; }
        .bg-indigo-50 { background-color: rgba(19, 127, 236, 0.05) !important; }

        /* Shadow Normalization */
        .card-shadow, .shadow-lg, .shadow-xl, .shadow-indigo-100 { 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important; 
        }

        /* Typography Normalization */
        .font-black, .font-bold { font-weight: 700 !important; }
        .italic { font-style: normal !important; }
        .uppercase { letter-spacing: 0.025em; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Dark Mode Support */
        .dark {
            --primary-bg: #0b1115;
            background-color: var(--primary-bg);
        }
        .dark body { color: #f1f5f9; background-color: var(--primary-bg); }

        .terminal-status { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: #10b981; display: flex; align-items: center; }
        .terminal-status .dot { height: 6px; width: 6px; background-color: #10b981; border-radius: 50%; display: inline-block; margin-right: 4px; animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
    </style>
</head>
<body x-data="{ 
    sidebarOpen: false, 
    darkMode: localStorage.getItem('darkMode') === 'true' 
}" :class="{ 'dark': darkMode }" class="text-slate-800 antialiased">
    
    <div id="global-loader" class="hidden fixed inset-0 z-[9999] grid place-items-center bg-white/70 dark:bg-slate-900/70 backdrop-blur-sm transition-all duration-300">
        <div class="h-10 w-10 border-4 border-slate-200 dark:border-slate-700 border-t-[#137fec] rounded-full animate-spin"></div>
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

        <!-- Sidebar -->
        @include('utility.kasir.sidebar')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50 dark:bg-[#0b1115] transition-colors duration-200">
            <!-- Topbar -->
            <header class="h-14 sm:h-16 bg-white dark:bg-[#111a22] border-b border-slate-200 dark:border-[#1e2d3d] px-4 sm:px-6 flex items-center justify-between z-20 shrink-0 transition-colors duration-200">
                <div class="flex items-center gap-3 sm:gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 rounded-lg transition-colors active:scale-95">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="flex flex-col">
                        <h1 class="text-[8px] sm:text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest leading-none">@yield('page-title')</h1>
                        <div class="terminal-status mt-1 hidden sm:flex"><span class="dot"></span> TERMINAL KASIR AKTIF</div>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    @yield('header-actions')
                    
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-700 mx-1 hidden sm:block"></div>
                    
                    <div x-data="{ userMenu: false }" class="relative flex items-center gap-2 sm:gap-3">
                        <button @click="userMenu = !userMenu" class="text-right hidden sm:block group">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-tighter leading-none group-hover:text-[#137fec] transition-colors">Operator Kasir</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-white leading-none mt-1 group-hover:text-[#137fec] transition-colors">{{ Auth::user()->nama }}</p>
                        </button>
                        <button @click="userMenu = !userMenu" class="h-8 w-8 sm:h-9 sm:w-9 rounded-lg bg-[#137fec] text-white flex items-center justify-center text-[10px] font-black uppercase transition-transform hover:scale-105 active:scale-95 shadow-lg shadow-[#137fec]/20">
                            {{ substr(Auth::user()->nama ?? 'U', 0, 1) }}
                        </button>

                        <div x-show="userMenu" @click.away="userMenu = false" x-cloak x-transition class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#1e2d3d] rounded-xl shadow-2xl py-0 z-50 overflow-hidden">
                            <div class="px-4 py-3 bg-slate-50 dark:bg-[#192633] sm:hidden border-b border-slate-200 dark:border-[#233648]">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                                <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->nama }}</p>
                            </div>
                            <a href="{{ route('kasir.profile') }}" class="flex items-center px-4 py-2.5 text-xs sm:text-sm text-slate-700 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#1e2d3d]">Profil Saya</a>
                            <div class="border-t border-slate-100 dark:border-[#1e2d3d]"></div>
                            <button onclick="showLogoutModal()" class="w-full text-left flex items-center px-4 py-2.5 text-xs sm:text-sm text-red-600 hover:bg-slate-50 dark:hover:bg-[#1e2d3d] font-bold">Keluar</button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 custom-scrollbar">
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-100 dark:border-green-500/20 text-green-700 dark:text-green-400 rounded-lg text-sm font-bold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="hover:opacity-70">
                        <span class="material-symbols-outlined text-sm font-bold">close</span>
                    </button>
                </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Redesigned Logout Modal -->
    <div id="logout-modal" style="display: none;" class="hidden fixed inset-0 z-[1000] items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="hideLogoutModal()"></div>
        <div class="relative z-10 w-full max-w-sm bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#1e2d3d] rounded-2xl shadow-2xl p-6">
            <div class="bg-red-50 dark:bg-red-500/10 h-14 w-14 rounded-xl flex items-center justify-center text-red-600 mb-4">
                <span class="material-symbols-outlined text-3xl">logout</span>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Konfirmasi Keluar?</h3>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mb-6">Pilih "Keluar" di bawah ini jika Anda siap untuk mengakhiri sesi terminal Kasir Anda.</p>
            <div class="flex gap-3">
                <button onclick="hideLogoutModal()" class="flex-1 py-2.5 text-sm font-bold text-slate-500 bg-slate-100 dark:bg-[#1e2d3d] hover:bg-slate-200 dark:hover:bg-[#2d445b] rounded-lg transition-colors">Batal</button>
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
        function showLoader() { 
            const loader = document.getElementById('global-loader');
            if(loader) {
                loader.classList.remove('hidden');
                setTimeout(() => loader.classList.add('opacity-100'), 10);
            }
        }
        function hideLoader() {
            const loader = document.getElementById('global-loader');
            if(loader) {
                loader.classList.remove('opacity-100');
                setTimeout(() => loader.classList.add('hidden'), 300);
            }
        }
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const h = this.getAttribute('href');
                if (h && !h.startsWith('#') && !h.startsWith('javascript:') && this.getAttribute('target') !== '_blank') showLoader();
            });
        });
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() { if (!this.hasAttribute('data-no-loader')) showLoader(); });
        });
        window.addEventListener('pageshow', () => hideLoader());
        document.addEventListener('DOMContentLoaded', () => hideLoader());
    </script>
</body>
</html>
