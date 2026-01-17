<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    class="fixed md:static inset-y-0 left-0 w-64 bg-[#111a22] border-r border-slate-800 shrink-0 flex flex-col z-40 transition-transform duration-300 ease-in-out">
    <div class="p-8 flex items-center gap-3">
        <div class="w-10 h-10 bg-[#137fec] rounded-xl flex items-center justify-center text-white shadow-lg shadow-[#137fec]/20">
            <span class="material-symbols-outlined">analytics</span>
        </div>
        <span class="text-xl font-black tracking-tighter text-white uppercase italic">{{ $shopSettings['shop_name'] ?? 'LaundryBiz' }}</span>
    </div>

    <nav class="flex-1 px-4 space-y-1.5 mt-4 overflow-y-auto">
        <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4 italic">Management Suite</p>
        
        <a href="{{ route('owner.dashboard') }}" class="sidebar-item {{ request()->routeIs('owner.dashboard') ? 'sidebar-active' : 'text-[#92adc9]' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/5">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="text-xs font-bold uppercase tracking-widest">Dashboard</span>
        </a>

        <a href="{{ route('owner.reports') }}" class="sidebar-item {{ request()->routeIs('owner.reports') ? 'sidebar-active' : 'text-[#92adc9]' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/5">
            <span class="material-symbols-outlined">bar_chart</span>
            <span class="text-xs font-bold uppercase tracking-widest">Financial Reports</span>
        </a>

        <a href="{{ route('owner.pengeluaran.index') }}" class="sidebar-item {{ request()->routeIs('owner.pengeluaran.*') ? 'sidebar-active' : 'text-[#92adc9]' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/5">
            <span class="material-symbols-outlined">shopping_cart_checkout</span>
            <span class="text-xs font-bold uppercase tracking-widest">Expenses</span>
        </a>

        <div class="pt-6">
            <p class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-4 italic">System Authority</p>
            <a href="{{ route('owner.settings') }}" class="sidebar-item {{ request()->routeIs('owner.settings') ? 'sidebar-active' : 'text-[#92adc9]' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/5">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-xs font-bold uppercase tracking-widest">Shop Settings</span>
            </a>
            <a href="{{ route('owner.profile') }}" class="sidebar-item {{ request()->routeIs('owner.profile') ? 'sidebar-active' : 'text-[#92adc9]' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-white/5">
                <span class="material-symbols-outlined">person_pin</span>
                <span class="text-xs font-bold uppercase tracking-widest">Master Profile</span>
            </a>
        </div>
    </nav>

    <div class="p-6 mt-auto border-t border-slate-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 font-bold uppercase tracking-widest text-[10px] hover:bg-red-500/10 rounded-xl transition-all">
                <span class="material-symbols-outlined">logout</span>
                Secure Logoff
            </button>
        </form>
    </div>
</aside>
