@extends(auth()->user()->role == 'admin' ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Transaksi Baru | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Entry Transaksi (POS)')

@section('content')
<div x-data="posSystem()" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- Left: Service Selection -->
    <div class="lg:col-span-7 space-y-6">
        <!-- Search & Category -->
        <div class="bg-white dark:bg-[#111a22] p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center transition-colors duration-200">
            <div class="relative w-full md:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9]">search</span>
                <input type="text" x-model="search" placeholder="Cari layanan..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-[#233648] border-none rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium">
            </div>
            <div class="flex items-center space-x-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto custom-scrollbar no-scrollbar scroll-smooth">
                <button @click="filterCategory = 'all'" 
                        :class="filterCategory === 'all' ? 'bg-[#137fec] text-white shadow-lg shadow-[#137fec]/20' : 'bg-slate-50 dark:bg-[#233648] text-slate-500 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-[#2d445b] border border-slate-100 dark:border-[#324d67]'" 
                        class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap active:scale-95">Semua</button>
                @foreach($layanans->pluck('kategori.nama')->unique() as $cat)
                <button @click="filterCategory = '{{ $cat }}'" 
                        :class="filterCategory === '{{ $cat }}' ? 'bg-[#137fec] text-white shadow-lg shadow-[#137fec]/20' : 'bg-slate-50 dark:bg-[#233648] text-slate-500 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-[#2d445b] border border-slate-100 dark:border-[#324d67]'" 
                        class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap active:scale-95">{{ $cat }}</button>
                @endforeach
            </div>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <template x-for="item in filteredLayanans" :key="item.id">
                <div @click="addToCart(item)" class="bg-white dark:bg-[#111a22] p-5 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm cursor-pointer hover:border-[#137fec] hover:shadow-md transition-all group overflow-hidden relative">
                    <div class="h-10 w-10 bg-[#137fec]/10 border border-[#137fec]/20 rounded-xl flex items-center justify-center text-[#137fec] mb-4 group-hover:bg-[#137fec] group-hover:text-white transition-all">
                        <span class="material-symbols-outlined">add</span>
                    </div>
                    <h4 x-text="item.nama" class="font-bold text-slate-800 dark:text-white leading-tight text-sm"></h4>
                    <p x-text="item.kategori.nama" class="text-[9px] text-slate-400 dark:text-[#92adc9] font-bold uppercase tracking-widest mt-1"></p>
                    <div class="mt-4 flex items-center justify-between">
                        <span x-text="'Rp ' + formatNumber(item.harga)" class="text-sm font-bold text-[#137fec]"></span>
                        <span x-text="'/' + item.satuan" class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690]"></span>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Right: Cart & Detail -->
    <div class="lg:col-span-5 space-y-6">
        <!-- Customer Selection -->
        <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm transition-colors duration-200">
            <h3 class="text-[10px] font-bold text-slate-400 dark:text-[#92adc9] uppercase tracking-widest mb-4">Informasi Pelanggan</h3>
            
            <div class="relative mb-4" @click.outside="openPelangganDropdown = false">
                <!-- Trigger -->
                <div @click="openPelangganDropdown = !openPelangganDropdown" 
                    class="w-full bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg px-4 py-3 text-sm font-bold flex items-center justify-between cursor-pointer hover:border-[#137fec] transition-all group">
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

            <div class="flex gap-4">
                <input type="text" x-model="parfum" placeholder="Pilihan Parfum..." class="flex-1 bg-slate-50 dark:bg-[#233648] border-none rounded-lg px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
            </div>
        </div>

        <!-- Cart Items -->
        <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden flex flex-col min-h-[400px] transition-colors duration-200">
            <div class="p-6 border-b border-slate-100 dark:border-[#324d67] flex items-center justify-between bg-slate-50/50 dark:bg-[#192633]">
                <h3 class="font-bold text-slate-800 dark:text-white uppercase tracking-tight text-sm">Keranjang Pesanan</h3>
                <span x-text="cart.length + ' Item'" class="text-[10px] bg-[#137fec]/10 text-[#137fec] px-2.5 py-1 rounded-lg font-bold border border-[#137fec]/20 uppercase"></span>
            </div>
            
            <div class="flex-1 overflow-y-auto p-6 max-h-[400px] space-y-6 custom-scrollbar">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="flex items-center justify-between group">
                        <div class="flex-1 min-w-0 pr-2">
                            <p x-text="item.nama" class="font-bold text-slate-800 dark:text-white text-sm truncate"></p>
                            <p x-text="'Rp ' + formatNumber(item.harga) + ' /' + item.satuan" class="text-[9px] sm:text-[10px] text-slate-400 dark:text-[#92adc9] font-bold uppercase tracking-wider"></p>
                        </div>
                        <div class="flex items-center space-x-2 sm:space-x-3 bg-slate-50 dark:bg-[#233648] p-1 sm:p-1.5 rounded-lg border border-slate-100 dark:border-[#324d67]">
                            <button @click="decreaseQty(index)" class="h-6 w-6 sm:h-8 sm:w-8 bg-white dark:bg-[#111a22] text-slate-600 dark:text-gray-300 rounded-md flex items-center justify-center hover:bg-slate-100 dark:hover:bg-[#2d445b] transition-all shadow-sm active:scale-90">
                                <span class="material-symbols-outlined text-xs sm:text-sm font-bold">remove</span>
                            </button>
                            <span x-text="item.jumlah" class="text-xs sm:text-sm font-black w-4 sm:w-6 text-center text-slate-800 dark:text-white"></span>
                            <button @click="increaseQty(index)" class="h-6 w-6 sm:h-8 sm:w-8 bg-[#137fec] text-white rounded-md flex items-center justify-center hover:bg-[#137fec]/90 transition-all shadow-md active:scale-90">
                                <span class="material-symbols-outlined text-xs sm:text-sm font-bold">add</span>
                            </button>
                        </div>
                        <button @click="removeFromCart(index)" class="ml-2 sm:ml-4 text-slate-300 hover:text-red-500 transition-all opacity-100 sm:opacity-0 sm:group-hover:opacity-100">
                            <span class="material-symbols-outlined text-xl">delete</span>
                        </button>
                    </div>
                </template>
                <div x-show="cart.length === 0" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                    <span class="material-symbols-outlined text-5xl mb-4 opacity-20">shopping_cart</span>
                    <p class="italic text-sm">Keranjang masih kosong.</p>
                </div>
            </div>

            <!-- Totals Area -->
            <div class="bg-slate-900 p-6 sm:p-8 text-white relative">
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center opacity-80">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Subtotal</span>
                        <span x-text="'Rp ' + formatNumber(subtotal)" class="font-bold text-sm"></span>
                    </div>
                    <div class="flex justify-between items-center text-red-400">
                        <span class="text-[10px] font-bold uppercase tracking-widest">Diskon</span>
                        <div class="flex items-center space-x-2">
                            <span>-</span>
                            <input type="number" x-model="diskon" class="w-20 sm:w-24 bg-white/10 border-none rounded-lg px-2 py-1 text-right text-xs font-bold text-red-400 focus:ring-1 focus:ring-red-400 outline-none">
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Total Akhir</span>
                        <span x-text="'Rp ' + formatNumber(total)" class="text-xl sm:text-2xl font-black text-[#137fec] tracking-tight italic"></span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="space-y-2">
                        <label class="block text-[8px] font-bold uppercase tracking-widest text-slate-500 italic">Metode Bayar</label>
                        <select x-model="metodePembayaran" class="w-full bg-white/10 border-none rounded-lg px-2 sm:px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-[#137fec] outline-none">
                            <option value="tunai" class="text-slate-900">Tunai</option>
                            <option value="transfer" class="text-slate-900">Transfer</option>
                            <option value="qris" class="text-slate-900">QRIS</option>
                            <option value="ewallet" class="text-slate-900">E-Wallet</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[8px] font-bold uppercase tracking-widest text-slate-500 italic">Jumlah Bayar</label>
                        <input type="number" x-model.number="jumlahBayar" class="w-full bg-white/10 border-none rounded-lg px-2 sm:px-3 py-2 text-xs font-bold text-white focus:ring-2 focus:ring-[#137fec] text-right outline-none">
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="mt-6 p-3.5 rounded-xl bg-white/5 border border-white/10 flex justify-between items-center">
                    <template x-if="jumlahBayar >= total && jumlahBayar > 0">
                        <div class="flex justify-between w-full items-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-green-400 italic">Kembalian</span>
                            <span x-text="'Rp ' + formatNumber(kembalian)" class="text-base sm:text-lg font-black text-green-400 italic"></span>
                        </div>
                    </template>
                    <template x-if="jumlahBayar < total && jumlahBayar > 0">
                        <div class="flex justify-between w-full items-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-red-400 italic">Kurang</span>
                            <span x-text="'Rp ' + formatNumber(total - jumlahBayar)" class="text-xs sm:text-sm font-black text-red-400 italic"></span>
                        </div>
                    </template>
                    <template x-if="jumlahBayar == 0">
                        <div class="flex justify-between w-full items-center">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 italic">Status</span>
                            <span class="text-[9px] font-black text-[#137fec] uppercase tracking-[0.2em] border border-[#137fec]/30 px-2.5 py-1 rounded-lg italic">NOT PAID</span>
                        </div>
                    </template>
                </div>
                
                <button @click="submitTransaksi()" :disabled="cart.length === 0 || !pelangganId || loading || (jumlahBayar > 0 && jumlahBayar < total)" class="w-full mt-6 py-4 bg-[#137fec] rounded-xl font-black uppercase tracking-[0.2em] text-xs sm:text-sm hover:bg-[#137fec]/90 shadow-xl shadow-[#137fec]/20 active:scale-[0.98] transition-all disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed italic">
                   <span x-show="!loading">Proses Transaksi</span>
                   <span x-show="loading" class="flex items-center justify-center gap-2">
                       <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                       Memproses...
                   </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Sticky Mobile Summary -->
    <div x-show="cart.length > 0" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="fixed bottom-0 left-0 right-0 p-4 bg-white/80 dark:bg-[#111a22]/80 backdrop-blur-md border-t border-slate-200 dark:border-[#324d67] z-50 lg:hidden flex justify-between items-center shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.1)]">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic leading-none mb-1">Total Pesanan</p>
            <p x-text="'Rp ' + formatNumber(total)" class="text-xl font-black text-slate-800 dark:text-white tracking-tighter italic"></p>
        </div>
        <button @click="document.querySelector('.lg\\:col-span-5').scrollIntoView({ behavior: 'smooth' })" class="px-6 py-3 bg-[#137fec] text-white rounded-xl font-bold uppercase tracking-widest text-[10px] shadow-lg shadow-[#137fec]/30 flex items-center gap-2 italic">
            <span>Checkout</span>
            <span class="material-symbols-outlined text-sm">arrow_downward</span>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            diskon: 0,
            jumlahBayar: 0,
            metodePembayaran: 'tunai',
            loading: false,
            cart: [],
            layanans: @json($layanans),
            pelanggans: [],

            init() {
                this.$watch('searchPelanggan', (value) => {
                    this.fetchPelanggans(value);
                });
                this.fetchPelanggans('');
            },

            async fetchPelanggans(query) {
                try {
                    const response = await fetch(`{{ route('admin.pelanggan') }}?q=${query}`, {
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
                    const matchesSearch = l.nama.toLowerCase().includes(this.search.toLowerCase());
                    const matchesCategory = this.filterCategory === 'all' || l.kategori.nama === this.filterCategory;
                    return matchesSearch && matchesCategory;
                });
            },
            
            get filteredPelanggansList() {
                if (!this.searchPelanggan) return this.pelanggans;
                const lower = this.searchPelanggan.toLowerCase();
                return this.pelanggans.filter(p => 
                    p.nama.toLowerCase().includes(lower) || 
                    p.kode_pelanggan.toLowerCase().includes(lower)
                );
            },
            
            get selectedPelangganText() {
                if (!this.pelangganId) return 'Pilih Pelanggan...';
                const p = this.pelanggans.find(p => p.id === this.pelangganId);
                return p ? `${p.nama} (${p.kode_pelanggan})` : 'Pilih Pelanggan...';
            },
            
            selectPelanggan(p) {
                this.pelangganId = p.id;
                this.openPelangganDropdown = false;
            },

            get subtotal() {
                return this.cart.reduce((acc, item) => acc + (item.harga * item.jumlah), 0);
            },

            get total() {
                return Math.max(0, this.subtotal - this.diskon);
            },

            get kembalian() {
                return Math.max(0, this.jumlahBayar - this.total);
            },

            addToCart(item) {
                const existing = this.cart.find(c => c.id === item.id);
                if (existing) {
                    existing.jumlah++;
                } else {
                    this.cart.push({
                        id: item.id,
                        layanan_id: item.id,
                        nama: item.nama,
                        harga: item.harga,
                        satuan: item.satuan,
                        jumlah: 1
                    });
                }
            },

            removeFromCart(index) {
                this.cart.splice(index, 1);
            },

            increaseQty(index) {
                this.cart[index].jumlah++;
            },

            decreaseQty(index) {
                if (this.cart[index].jumlah > 1) {
                    this.cart[index].jumlah--;
                } else {
                    this.removeFromCart(index);
                }
            },

            formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            },

            async submitTransaksi() {
                this.loading = true;
                
                try {
                    const response = await fetch('{{ route(auth()->user()->role . ".transaksi.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            pelanggan_id: this.pelangganId,
                            items: this.cart,
                            diskon: this.diskon,
                            jumlah_bayar: this.jumlahBayar,
                            metode_pembayaran: this.metodePembayaran,
                            parfum: this.parfum
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: result.message,
                            icon: 'success',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#137fec'
                        }).then(() => {
                            window.location.href = result.redirect;
                        });
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'Coba Lagi',
                        confirmButtonColor: '#EF4444'
                    });
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endpush
