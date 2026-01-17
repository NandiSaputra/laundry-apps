<aside id="sidebar-wrapper"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:static inset-y-0 left-0 w-64 flex flex-col bg-white dark:bg-[#111a22] border-r border-slate-200 dark:border-[#1e2d3d] shrink-0 z-40 transition-all duration-300 ease-in-out">
    
    <!-- Brand -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-[#1e2d3d] transition-colors duration-200">
        <a href="/" class="flex items-center gap-3">
        @if(isset($shopSettings['shop_logo']) && $shopSettings['shop_logo'])
            <div class="h-10 flex items-center justify-center overflow-hidden py-1">
                <img src="{{ asset('storage/' . $shopSettings['shop_logo']) }}" alt="Logo" class="h-full w-auto object-contain max-w-[40px]">
            </div>
        @else
            <div class="h-10 w-10 bg-[#137fec] rounded-lg flex items-center justify-center shadow-lg shadow-[#137fec]/20">
                <span class="material-symbols-outlined text-white text-2xl font-bold">local_laundry_service</span>
            </div>
        @endif
            <span class="font-bold text-slate-800 dark:text-white tracking-tight uppercase text-sm">{{ $shopSettings['shop_name'] ?? 'LaundryBiz' }}</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
        @php
            $role = Auth::user()->role;
            $navItems = [
                ['header' => 'Main Menu'],
                ['route' => 'kasir.dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['header' => 'Transactions'],
                ['route' => 'kasir.transaksi.baru', 'label' => 'Transaksi Baru', 'icon' => 'add_shopping_cart'],
                ['route' => 'kasir.transaksi.index', 'label' => 'Monitor Order', 'icon' => 'monitor_heart'],
                ['route' => 'kasir.pengeluaran.index', 'label' => 'Pengeluaran', 'icon' => 'payments'],
                ['header' => 'Database'],
                ['route' => 'kasir.layanan', 'label' => 'Daftar Harga', 'icon' => 'sell'],
                ['route' => 'kasir.pelanggan', 'label' => 'Data Pelanggan', 'icon' => 'group'],
            ];
        @endphp

        @foreach($navItems as $item)
            @if(isset($item['header']))
                <div class="px-3 pt-6 pb-2 text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em]">{{ $item['header'] }}</div>
            @else
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                   {{ request()->routeIs($item['route'] . '*') ? 'bg-[#137fec]/10 text-[#137fec]' : 'text-slate-600 dark:text-[#92adc9] hover:bg-slate-50 dark:hover:bg-white/5' }}">
                    <span class="material-symbols-outlined text-[22px] transition-transform group-hover:scale-110 {{ request()->routeIs($item['route'] . '*') ? 'FILL' : '' }}">{{ $item['icon'] }}</span>
                    <span class="text-sm font-bold tracking-tight">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>
    
    <!-- User Profile Footer -->
    <div class="p-4 border-t border-slate-100 dark:border-[#1e2d3d] bg-slate-50/50 dark:bg-[#0b1115] transition-colors duration-200">
        <div class="flex items-center justify-between gap-3 p-2 rounded-xl bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#1e2d3d] shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-[#137fec] text-white flex items-center justify-center font-bold text-xs uppercase shadow-md shadow-[#137fec]/20">
                    {{ substr(Auth::user()->nama ?? 'U', 0, 1) }}
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate leading-none mb-1">{{ Auth::user()->nama }}</span>
                    <span class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest leading-none">Terminal Connected</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-all" title="Logout">
                    <span class="material-symbols-outlined text-xl">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
