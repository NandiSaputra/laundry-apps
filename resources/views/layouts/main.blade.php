<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $shopSettings['shop_name'] ?? 'Lavandera Premium Care')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .sidebar-item:hover { background: rgba(79, 70, 229, 0.1); color: #4F46E5; }
        .sidebar-item.active { background: #4F46E5; color: white; }
        .card-shadow { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05); }
        [x-cloak] { display: none !important; }
        @stack('styles')
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="@yield('body-class', 'bg-gray-50 text-gray-800')">
    <!-- Global Full Screen Loader -->
    <div id="global-loader" class="hidden fixed inset-0 z-9999 grid place-items-center bg-white/50 backdrop-blur-xl transition-all duration-500 opacity-0">
        <div class="flex flex-col items-center">
            <div class="relative group">
                <!-- Rotating Ring -->
                <div class="h-24 w-24 rounded-full border-[3px] border-indigo-50 border-t-indigo-600 animate-[spin_0.8s_linear_infinite]"></div>
                <!-- Inner Pulse -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="h-16 w-16 rounded-full bg-indigo-50/50 animate-pulse flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex flex-col items-center">
                <span class="text-xl font-black text-gray-900 tracking-tighter italic uppercase">{{ $shopSettings['shop_name'] ?? 'Lavandera' }}</span>
                <div class="mt-1 h-1 w-8 bg-indigo-600 rounded-full animate-bounce"></div>
            </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden relative">
        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-30 md:hidden" x-cloak></div>
        <!-- Sidebar -->
        @include('utility.admin.sidebar')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 @yield('main-bg', 'bg-gray-50') overflow-y-auto">
            <!-- Header -->
            <header class="@yield('header-class', 'bg-white/80 backdrop-blur-md border-b border-gray-100') flex items-center justify-between px-8 py-4 sticky top-0 z-20">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen; console.log('Sidebar toggled:', sidebarOpen)" class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg active:scale-90 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold @yield('header-text-class', 'text-gray-900')">@yield('page-title')</h1>
                </div>

                <div class="flex items-center space-x-4">
                    @yield('header-actions')
                    
                    <button type="button" onclick="showLogoutModal()" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>

                    <div class="h-8 w-8 rounded-full bg-linear-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-md cursor-pointer">
                        {{ strtoupper(substr(Auth::user()->nama ?? 'AD', 0, 2)) }}
                    </div>
                </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="hidden fixed inset-0 z-9999 items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="hideLogoutModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all animate-fade-in-up">
            <div class="p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-indigo-50 text-indigo-600 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Keluar</h3>
                <p class="text-gray-500 mb-8">Apakah Anda yakin ingin mengakhiri sesi ini dan keluar dari sistem?</p>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <form action="{{ route('logout') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                            Ya, Keluar
                        </button>
                    </form>
                    <button type="button" onclick="hideLogoutModal()" class="flex-1 py-3 bg-gray-50 text-gray-700 rounded-xl font-bold hover:bg-gray-100 transition-all">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global Loader Functions
        function showLoader() {
            const loader = document.getElementById('global-loader');
            loader.classList.remove('hidden');
            loader.classList.add('grid');
            setTimeout(() => {
                loader.classList.add('opacity-100');
            }, 10);
        }

        function hideLoader() {
            const loader = document.getElementById('global-loader');
            loader.classList.remove('opacity-100');
            setTimeout(() => {
                loader.classList.add('hidden');
                loader.classList.remove('grid');
            }, 300);
        }

        // Auto-show loader on navigation
        window.addEventListener('beforeunload', () => {
            showLoader();
        });

        // Show/Hide loader for forms
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.hasAttribute('data-no-loader')) return;
            showLoader();
        });

        // Logout Modal Functions
        function showLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
    @stack('scripts')
</body>
</html>
