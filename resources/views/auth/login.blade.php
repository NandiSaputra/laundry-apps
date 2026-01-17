<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ $shopSettings['shop_name'] ?? 'LaundryBiz' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#0b1116] flex items-center justify-center min-h-screen p-4 transition-colors">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-[#111a22] rounded-2xl p-10 shadow-2xl border border-slate-100 dark:border-[#233648] transition-colors">
            <!-- Logo & Brand -->
            <div class="flex flex-col items-center mb-10">
                <div class="h-16 flex items-center justify-center mb-6 overflow-hidden">
                    @if(isset($shopSettings['shop_logo']) && $shopSettings['shop_logo'])
                        <img src="{{ asset('storage/' . $shopSettings['shop_logo']) }}" alt="Logo" class="h-16 w-auto object-contain max-w-[200px]">
                    @else
                        <div class="w-16 h-16 bg-[#137fec] rounded-2xl flex items-center justify-center text-white shadow-xl shadow-[#137fec]/20">
                            <span class="material-symbols-outlined text-4xl">local_laundry_service</span>
                        </div>
                    @endif
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight uppercase">{{ $shopSettings['shop_name'] ?? 'LaundryBiz' }}</h1>
                <p class="text-slate-400 dark:text-[#92adc9] mt-2 text-[10px] font-bold uppercase tracking-[0.3em]">Akses Terminal Institusi</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-1.5">
                    <label for="email" class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-widest">Alamat Email/ID</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-[#137fec] transition-colors">
                            <span class="material-symbols-outlined text-xl">person</span>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-900 dark:text-white transition-all" 
                            placeholder="nama@laundrybiz.com">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-[10px] mt-2 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] ml-1 uppercase tracking-widest">Kunci Otentikasi Rahasia</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 group-focus-within:text-[#137fec] transition-colors">
                            <span class="material-symbols-outlined text-xl">lock</span>
                        </span>
                        <input type="password" name="password" id="password" required 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-900 dark:text-white transition-all" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between ml-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-200 dark:border-[#324d67] text-[#137fec] focus:ring-[#137fec] bg-slate-50 dark:bg-[#233648]">
                        <span class="text-[9px] font-bold text-slate-500 dark:text-[#92adc9] uppercase tracking-widest group-hover:text-[#137fec] transition-colors">Tetap Aktifkan Sesi</span>
                    </label>
                    <a href="#" class="text-[9px] font-bold text-[#137fec] uppercase tracking-widest hover:underline">Lupa Kunci?</a>
                </div>

                <button type="submit" 
                    class="w-full py-4 px-6 bg-[#137fec] text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-[#137fec]/90 shadow-xl shadow-[#137fec]/20 transition-all active:scale-[0.98]">
                    Otorisasi Akses
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 dark:border-[#233648] text-center">
                <p class="text-[8px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-[0.2em]">
                    Powered by <span class="text-[#137fec]">LaundryBiz OS</span> &bull; v1.2.0
                </p>
            </div>
        </div>
    </div>
</body>
</html>
