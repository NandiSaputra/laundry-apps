<aside 
    id="sidebar-wrapper"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:static inset-y-0 left-0 w-64 flex flex-col bg-white dark:bg-[#111a22] border-r border-slate-200 dark:border-[#233648] transition-all duration-300 ease-in-out z-40"
>
    <!-- Brand Logo Section -->
    <div class="p-6 flex items-center gap-3">
        @if(isset($shopSettings['shop_logo']) && $shopSettings['shop_logo'])
            <div class="h-10 flex items-center justify-center overflow-hidden py-1">
                <img src="{{ asset('storage/' . $shopSettings['shop_logo']) }}" alt="Logo" class="h-full w-auto object-contain max-w-[40px]">
            </div>
        @else
            <div class="h-10 w-10 bg-[#137fec] rounded-lg flex items-center justify-center shadow-lg shadow-[#137fec]/20">
                <span class="material-symbols-outlined text-white text-2xl font-bold">local_laundry_service</span>
            </div>
        @endif
        <div>
            <h1 class="text-base font-bold leading-none text-slate-800 dark:text-white">{{ $shopSettings['shop_name'] ?? 'LaundryBiz' }}</h1>
            <p class="text-[10px] text-slate-500 dark:text-[#92adc9] uppercase font-bold tracking-widest mt-1">Manajemen Pro</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto custom-scrollbar pb-6">
        @php
            $role = Auth::user()->role;
            $currentRoute = request()->route()->getName();
        @endphp

        <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mb-2">Menu Utama</div>
        
        <a href="{{ route($role . '.dashboard') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs($role . '.dashboard') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
            <span class="material-symbols-outlined text-xl">dashboard</span>
            <span class="text-sm font-semibold">Dashboard</span>
        </a>

        @if($role === 'admin' || $role === 'kasir')
            <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mt-6 mb-2">Transaksi</div>
            
            <a href="{{ route($role . '.transaksi.baru') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs($role . '.transaksi.baru') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">add_shopping_cart</span>
                <span class="text-sm font-semibold">Pesanan Baru</span>
            </a>

            <a href="{{ route($role . '.transaksi.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs($role . '.transaksi.index') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">list_alt</span>
                <span class="text-sm font-semibold">Pantau Pesanan</span>
            </a>

            <a href="{{ route($role . '.pengeluaran.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->is($role . '/pengeluaran*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                <span class="text-sm font-semibold">Pengeluaran</span>
            </a>
        @elseif($role === 'owner')
            <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mt-6 mb-2">Keuangan</div>
            <a href="{{ route('owner.pengeluaran.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->is('owner/pengeluaran*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">account_balance_wallet</span>
                <span class="text-sm font-semibold">Data Pengeluaran</span>
            </a>
        @elseif($role === 'produksi')
            <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mt-6 mb-2">Operasional</div>
            
            <a href="{{ route('produksi.transaksi.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('produksi.transaksi.index') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">list_alt</span>
                <span class="text-sm font-semibold">Daftar Antrian</span>
            </a>
        @endif

        @if($role === 'admin' || $role === 'kasir')
            <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mt-6 mb-2">Basis Data</div>
            
            @if($role === 'admin')
                <a href="{{ route($role . '.reports') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->is($role . '/report*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                    <span class="material-symbols-outlined text-xl">analytics</span>
                    <span class="text-sm font-semibold">Laporan</span>
                </a>
            @endif

            <a href="{{ route($role . '.pelanggan') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs($role . '.pelanggan*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">group</span>
                <span class="text-sm font-semibold">Pelanggan</span>
            </a>

            <a href="{{ route($role . '.layanan') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs($role . '.layanan*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">inventory_2</span>
                <span class="text-sm font-semibold">Layanan</span>
            </a>
        @elseif($role === 'owner')
             <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mt-6 mb-2">Audit & Analitik</div>
             <a href="{{ route('owner.reports') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->is('owner/report*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                 <span class="material-symbols-outlined text-xl">analytics</span>
                 <span class="text-sm font-semibold">Pusat Laporan</span>
             </a>
        @endif

        @if($role === 'admin' || $role === 'owner')
            <div class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest px-3 mt-6 mb-2">Sistem</div>
            
            @if($role === 'admin')
                <a href="{{ route('admin.user') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->is('admin/user*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                    <span class="material-symbols-outlined text-xl">person_settings</span>
                    <span class="text-sm font-semibold">Manajemen User</span>
                </a>
            @endif

            <a href="{{ route($role . '.settings') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->is($role . '/settings*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-gray-300 hover:bg-slate-50 dark:hover:bg-[#233648]' }}">
                <span class="material-symbols-outlined text-xl">settings</span>
                <span class="text-sm font-semibold">Pengaturan Toko</span>
            </a>
        @endif
    </nav>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-200 dark:border-[#233648]">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-[#233648] transition-colors cursor-pointer group">
            <div class="bg-[#137fec]/10 size-10 rounded-full flex items-center justify-center text-[#137fec] font-bold text-sm border border-[#137fec]/20 group-hover:bg-[#137fec] group-hover:text-white transition-all">
                {{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->nama }}</p>
                <p class="text-[10px] text-slate-500 dark:text-[#92adc9] truncate font-bold uppercase">{{ Auth::user()->role }}</p>
            </div>
            <button onclick="showLogoutModal()" class="text-slate-400 hover:text-red-500">
                <span class="material-symbols-outlined text-xl text-slate-400 hover:text-red-600 transition-colors">logout</span>
            </button>
        </div>
    </div>
</aside>
