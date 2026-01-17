@extends('layouts.kasir')

@section('title', 'POS Terminal | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Entry Transaksi')

@section('content')
<div x-data="posSystem()" class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
    
    <!-- Left: Service Selection -->
    <div class="xl:col-span-8 space-y-6">
        <!-- Controls -->
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-80 group">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 group-focus-within:text-[#137fec] transition-colors">
                    <span class="material-symbols-outlined text-xl">search</span>
                </span>
                <input type="text" x-model="search" placeholder="Cari layanan (Nama/Kategori)..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#137fec] outline-none transition-all dark:text-white">
            </div>
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto">
                <button @click="filterCategory = 'all'" 
                    :class="filterCategory === 'all' ? 'bg-[#137fec] text-white shadow-lg shadow-[#137fec]/20 border-transparent' : 'bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] border-slate-200 dark:border-[#324d67]'" 
                    class="px-5 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest border transition-all whitespace-nowrap">Semua</button>
                @foreach($layanans->pluck('kategori.nama')->unique() as $cat)
                <button @click="filterCategory = '{{ $cat }}'" 
                    :class="filterCategory === '{{ $cat }}' ? 'bg-[#137fec] text-white shadow-lg shadow-[#137fec]/20 border-transparent' : 'bg-slate-100 dark:bg-[#233648] text-slate-500 dark:text-[#92adc9] border-slate-200 dark:border-[#324d67]'" 
                    class="px-5 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest border transition-all whitespace-nowrap">{{ $cat }}</button>
                @endforeach
            </div>
        </div>

        <!-- Catalog Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-4">
            <template x-for="item in filteredLayanans" :key="item.id">
                <div @click="addToCart(item)" 
                    class="bg-white dark:bg-[#111a22] p-5 rounded-xl border border-slate-200 dark:border-[#324d67] cursor-pointer hover:border-[#137fec] dark:hover:border-[#137fec] hover:scale-[1.02] active:scale-95 transition-all group relative overflow-hidden flex flex-col h-full shadow-sm">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-10 w-10 bg-slate-50 dark:bg-[#233648] rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-[#137fec] group-hover:text-white transition-all border border-slate-100 dark:border-[#324d67]">
                            <span class="material-symbols-outlined text-xl">add_box</span>
                        </div>
                        <span class="text-[8px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest italic" x-text="item.kategori.nama"></span>
                    </div>
                    <div class="flex-1">
                        <h4 x-text="item.nama" class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-tight leading-tight"></h4>
                    </div>
                    <div class="mt-4 flex items-end justify-between">
                        <div>
                            <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Unit Rate</p>
                            <span class="text-xs font-bold text-[#137fec]" x-text="'Rp ' + formatNumber(item.harga)"></span>
                        </div>
                        <span x-text="'/' + item.satuan" class="text-[9px] font-bold text-slate-400 italic"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Right: Checkout Console -->
    <div class="xl:col-span-4 space-y-4 sticky top-20">
        <!-- Customer Info -->
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm space-y-5">
            <div class="relative" @click.outside="openPelangganDropdown = false">
                <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-2 block">Entri Member Terminal</label>
                
                <!-- Trigger -->
                <div @click="openPelangganDropdown = !openPelangganDropdown" 
                    class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2.5 text-sm font-bold flex items-center justify-between cursor-pointer hover:border-[#137fec] transition-all group">
                    <span :class="pelangganId ? 'text-slate-800 dark:text-white' : 'text-slate-400'" x-text="selectedPelangganText"></span>
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-[#137fec] transition-colors text-lg">expand_more</span>
                </div>

                <!-- Dropdown Menu -->
                <div x-show="openPelangganDropdown" x-transition.opacity.duration.200ms
                    class="absolute z-50 top-full left-0 right-0 mt-2 bg-white dark:bg-[#111a22] border border-slate-200 dark:border-[#324d67] rounded-xl shadow-2xl overflow-hidden max-h-[300px] flex flex-col">
                    
                    <!-- Search Input -->
                    <div class="p-3 border-b border-slate-100 dark:border-[#324d67] sticky top-0 bg-white dark:bg-[#111a22]">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <span class="material-symbols-outlined text-lg">search</span>
                            </span>
                            <input type="text" x-model="searchPelanggan" placeholder="Cari Nama / Kode / HP / Alamat..." 
                                class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg text-xs font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all uppercase">
                        </div>
                    </div>

                    <!-- List -->
                    <div class="overflow-y-auto flex-1 p-2 space-y-1">
                        <template x-for="p in filteredPelanggansList" :key="p.id">
                            <div @click="selectPelanggan(p)" 
                                class="p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-[#233648] cursor-pointer group transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-white text-xs uppercase" x-text="p.nama"></p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">
                                            <span x-text="'[' + p.kode_pelanggan + ']'"></span>
                                            <span x-show="p.alamat" x-text="' • ' + p.alamat"></span>
                                        </p>
                                    </div>
                                    <span x-show="pelangganId === p.id" class="material-symbols-outlined text-[#137fec] text-lg">check_circle</span>
                                </div>
                            </div>
                        </template>
                        <div x-show="filteredPelanggansList.length === 0" class="p-4 text-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tidak ada pelanggan ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">Pilihan Parfum</label>
                    <input type="text" x-model="parfum" placeholder="Opsional..." 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2 text-xs font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest ml-1">Catatan Transaksi</label>
                    <textarea x-model="catatan" rows="2" placeholder="Instruksi khusus..." 
                        class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-2 text-xs font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all"></textarea>
                </div>
            </div>
        </div>

        <!-- Cart Summary -->
        <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-[#324d67] flex items-center justify-between bg-slate-50/50 dark:bg-[#192633]">
                <div>
                    <h3 class="font-bold text-slate-800 dark:text-white uppercase text-sm tracking-tight">Konsol Keranjang</h3>
                    <p class="text-[8px] text-slate-400 font-bold tracking-[0.2em] uppercase mt-0.5">Terminal ID: {{ strtoupper(substr(uniqid(), -8)) }}</p>
                </div>
                <span x-text="cart.length + ' Items'" class="text-[10px] font-bold bg-[#137fec]/10 text-[#137fec] px-3 py-1 rounded-lg border border-[#137fec]/20 uppercase"></span>
            </div>
            
            <!-- Items List -->
            <div class="flex-1 overflow-y-auto px-6 py-4 min-h-[200px] max-h-[350px] space-y-3">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between group p-3 bg-slate-50 dark:bg-[#192633] rounded-xl border border-transparent hover:border-[#137fec]/30 transition-all">
                        <div class="flex-1">
                            <p x-text="item.nama" class="font-bold text-slate-800 dark:text-white text-xs uppercase tracking-tight leading-none mb-1"></p>
                            <p x-text="'Rp ' + formatNumber(item.harga) + ' /' + item.satuan" class="text-[9px] text-slate-400 font-bold italic"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="decreaseQty(index)" class="h-7 w-7 bg-white dark:bg-[#233648] text-slate-400 rounded-lg flex items-center justify-center hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all border border-slate-200 dark:border-[#324d67] shadow-sm">
                                <span class="material-symbols-outlined text-sm">remove</span>
                            </button>
                            <span x-text="item.jumlah" class="text-xs font-bold w-6 text-center text-[#137fec]"></span>
                            <button @click="increaseQty(index)" class="h-7 w-7 bg-[#137fec] text-white rounded-lg flex items-center justify-center hover:bg-[#137fec]/90 transition-all shadow-lg shadow-[#137fec]/20">
                                <span class="material-symbols-outlined text-sm">add</span>
                            </button>
                        </div>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center py-10 text-slate-300 dark:text-slate-700">
                    <span class="material-symbols-outlined text-5xl opacity-40">shopping_cart_off</span>
                    <p class="text-[9px] font-bold uppercase tracking-widest mt-3">Keranjang Kosong</p>
                </div>
            </div>

            <!-- Total & Action -->
            <div class="bg-slate-900 p-6 text-white transition-colors">
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-slate-400">
                        <span class="text-[9px] font-bold uppercase tracking-widest">Subtotal</span>
                        <span x-text="'Rp ' + formatNumber(subtotal)" class="text-xs font-bold tracking-tight"></span>
                    </div>
                    <div class="flex justify-between items-center text-red-400">
                        <span class="text-[9px] font-bold uppercase tracking-widest">Diskon</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold">-</span>
                            <input type="number" x-model="diskon" 
                                class="w-20 bg-white/5 border border-white/10 rounded-lg py-1 px-2 text-right text-xs font-bold text-red-400 focus:ring-1 focus:ring-red-400 outline-none">
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/10 flex justify-between items-end">
                        <div class="flex flex-col">
                            <span class="text-[8px] font-bold uppercase tracking-widest text-[#137fec] mb-1 leading-none">Total Akhir</span>
                            <span x-text="'Rp ' + formatNumber(total)" class="text-2xl font-bold tracking-tighter leading-none"></span>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 bg-green-500/10 text-green-400 text-[8px] font-bold uppercase tracking-widest rounded-md border border-green-500/20">SIAP</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="space-y-1">
                        <label class="text-[8px] font-bold uppercase tracking-widest text-slate-500">Metode</label>
                        <select x-model="metodePembayaran" class="w-full bg-white/10 border border-white/10 rounded-lg px-2 py-2 text-[10px] font-bold text-white focus:ring-2 focus:ring-[#137fec] outline-none">
                            <option value="tunai" class="text-slate-900">TUNAI (CASH)</option>
                            <option value="transfer" class="text-slate-900">BANK TRANSFER</option>
                            <option value="qris" class="text-slate-900">QRIS</option>
                            <option value="ewallet" class="text-slate-900">E-WALLET</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[8px] font-bold uppercase tracking-widest text-slate-500 text-right block">Dibayar</label>
                        <input type="number" x-model.number="jumlahBayar" 
                            class="w-full bg-white/10 border border-white/10 rounded-lg px-2 py-2 text-[10px] font-bold text-white focus:ring-2 focus:ring-[#137fec] text-right outline-none">
                    </div>
                </div>

                <div x-show="jumlahBayar > 0" class="p-3 rounded-lg bg-white/5 border border-white/10 mb-6 transition-all">
                    <template x-if="jumlahBayar >= total">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-green-400">Kembalian</span>
                            <span x-text="'Rp ' + formatNumber(kembalian)" class="text-lg font-bold text-green-400 tracking-tighter"></span>
                        </div>
                    </template>
                    <template x-if="jumlahBayar < total">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-red-400">Kurang Bayar</span>
                            <span x-text="'Rp ' + formatNumber(total - jumlahBayar)" class="text-lg font-bold text-red-400 tracking-tighter"></span>
                        </div>
                    </template>
                </div>
                
                <button @click="submitTransaksi()" 
                    :disabled="cart.length === 0 || !pelangganId || loading || (jumlahBayar > 0 && jumlahBayar < total)" 
                    class="w-full py-4 bg-[#137fec] rounded-xl font-bold uppercase tracking-widest text-xs hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all disabled:opacity-30 disabled:grayscale disabled:cursor-not-allowed">
                    <span x-show="!loading">Proses Transaksi</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Memproses...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function posSystem() {
        return {
            search: '',
            filterCategory: 'all',
            
            // New Searchable Customer Variables
            openPelangganDropdown: false,
            searchPelanggan: '',
            
            pelangganId: '',
            parfum: '',
            catatan: '',
            diskon: 0,
            jumlahBayar: 0,
            metodePembayaran: 'tunai',
            loading: false,
            cart: [],
            layanans: @json($layanans),
            pelanggans: [], // Start empty, load via AJAX

            init() {
                this.$watch('searchPelanggan', (value) => {
                    this.fetchPelanggans(value);
                });
                // Initial fetch (optional, maybe top 10)
                 this.fetchPelanggans('');
            },

            async fetchPelanggans(query) {
                try {
                    const response = await fetch(`{{ route('kasir.pelanggan') }}?q=${query}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    this.pelanggans = data;
                } catch (e) {
                    console.error('Failed to fetch customers', e);
                }
            },

            get filteredLayanans() {
                return this.layanans.filter(l => {
                    const searchLower = this.search.toLowerCase();
                    const matchesSearch = l.nama.toLowerCase().includes(searchLower) || 
                                       l.kategori.nama.toLowerCase().includes(searchLower);
                    const matchesCategory = this.filterCategory === 'all' || l.kategori.nama === this.filterCategory;
                    return matchesSearch && matchesCategory;
                });
            },
            
            get filteredPelanggansList() {
                // Since data is already filtered by server, just return it
                // Or if we want to support client-side filtering on the currently loaded localized set:
                // return this.pelanggans;
                // But for safety and consistency with server results:
                return this.pelanggans;
            },
            
            get selectedPelangganText() {
                if (!this.pelangganId) return '-- PILIH PELANGGAN --';
                const p = this.pelanggans.find(p => p.id === this.pelangganId);
                return p ? `${p.nama.toUpperCase()} [${p.kode_pelanggan}]` : '-- PILIH PELANGGAN --';
            },
            
            selectPelanggan(p) {
                this.pelangganId = p.id;
                this.openPelangganDropdown = false;
                // Optional: clear search on select
                // this.searchPelanggan = ''; 
            },

            get subtotal() { return this.cart.reduce((acc, item) => acc + (item.harga * item.jumlah), 0); },
            get total() { return Math.max(0, this.subtotal - this.diskon); },
            get kembalian() { return Math.max(0, this.jumlahBayar - this.total); },
            formatNumber(n) { return new Intl.NumberFormat('id-ID').format(n); },

            addToCart(item) {
                const existing = this.cart.find(c => c.id === item.id);
                if (existing) existing.jumlah++;
                else this.cart.push({ id: item.id, layanan_id: item.id, nama: item.nama, harga: item.harga, satuan: item.satuan, jumlah: 1 });
            },
            decreaseQty(i) { if (this.cart[i].jumlah > 1) this.cart[i].jumlah--; else this.cart.splice(i, 1); },
            increaseQty(i) { this.cart[i].jumlah++; },

            async submitTransaksi() {
                this.loading = true;
                try {
                    const response = await fetch('{{ route("kasir.transaksi.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({
                            pelanggan_id: this.pelangganId,
                            items: this.cart,
                            diskon: this.diskon,
                            jumlah_bayar: this.jumlahBayar,
                            metode_pembayaran: this.metodePembayaran,
                            parfum: this.parfum,
                            catatan: this.catatan
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            title: 'BERHASIL', text: result.message, icon: 'success', confirmButtonText: 'TUTUP', confirmButtonColor: '#137fec',
                            background: document.documentElement.classList.contains('dark') ? '#111a22' : '#fff',
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                            customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-2xl', confirmButton: 'rounded-lg px-8 py-2.5 font-bold uppercase tracking-widest text-xs' }
                        }).then(() => window.location.href = result.redirect);
                    } else throw new Error(result.message);
                } catch (e) {
                    Swal.fire({ title: 'GAGAL', text: e.message, icon: 'error', confirmButtonText: 'COBA LAGI', confirmButtonColor: '#ef4444',
                        background: document.documentElement.classList.contains('dark') ? '#111a22' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                        customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-[#324d67] shadow-2xl', confirmButton: 'rounded-lg px-8 py-2.5 font-bold uppercase tracking-widest text-xs' }
                    });
                } finally { this.loading = false; }
            }
        }
    }
</script>
@endpush
@endsection
