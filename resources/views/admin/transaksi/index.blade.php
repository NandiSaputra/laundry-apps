@extends(auth()->user()->role == 'admin' ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Monitor Transaksi | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Monitor Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header Summary -->
    <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex flex-col md:flex-row justify-between items-center gap-6 transition-colors duration-200">
        <div class="text-center md:text-left">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">Alur Kerja Laundry</h2>
            <p class="text-sm text-slate-500 dark:text-[#92adc9] mt-1">Pantau dan perbarui status cucian pelanggan secara real-time.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
            <!-- Scan Input -->
            <div class="relative flex items-center w-full sm:w-auto" x-data="scannerComponent()">
                <span class="material-symbols-outlined absolute left-3 text-slate-400 dark:text-[#92adc9]">qr_code_scanner</span>
                <input type="text" x-model="code" @input.debounce.500ms="handleScan()" placeholder="Pindai QR / Kode..." class="pl-10 pr-12 py-2.5 bg-slate-50 dark:bg-[#233648] border border-slate-200 dark:border-[#324d67] rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all w-full sm:w-48 md:w-64">
                <button @click="startScanner()" class="absolute right-2 p-1.5 bg-[#137fec] text-white rounded-md hover:bg-[#137fec]/90 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-sm">photo_camera</span>
                </button>

                <!-- Scanner Modal -->
                <div x-show="isScanning" 
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm" 
                    x-cloak>
                    <div class="bg-white dark:bg-[#111a22] rounded-2xl p-6 w-full max-w-md relative border border-slate-200 dark:border-[#324d67] shadow-2xl">
                        <button @click="stopScanner()" class="absolute -top-4 -right-4 bg-red-600 text-white p-2 rounded-full shadow-lg hover:bg-red-700 transition-all">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                        <h4 class="text-center font-bold text-slate-800 dark:text-white mb-4">Kamera Pemindai Aktif</h4>
                        <div id="reader" class="rounded-xl overflow-hidden border border-slate-200 dark:border-[#324d67]"></div>
                        <p class="text-center text-xs text-slate-400 dark:text-[#92adc9] mt-4 italic uppercase tracking-widest font-bold">Arahkan Kode QR ke area pemindai</p>
                    </div>
                </div>
            </div>
            
            <a href="{{ route(auth()->user()->role . '.transaksi.baru') }}" class="w-full sm:w-auto justify-center px-6 py-2.5 bg-[#137fec] text-white rounded-lg text-sm font-bold hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-xl">add_circle</span>
                <span>Input Order Baru</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-[#111a22] p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm transition-colors duration-200">
        <form action="{{ route(auth()->user()->role . '.transaksi.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Nama Pelanggan..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-[#233648] border-none rounded-lg text-sm focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium">
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="status" class="flex-1 bg-slate-50 dark:bg-[#233648] border-none rounded-lg px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="diambil" {{ request('status') == 'diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-slate-800 dark:bg-[#2d445b] text-white rounded-lg text-sm font-bold hover:bg-slate-900 transition-all uppercase tracking-wider">Filter</button>
            </div>
        </form>
    </div>

    <!-- Transaction Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($transactions as $trx)
        <div class="h-full">
            <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden group hover:border-[#137fec] hover:shadow-md transition-all duration-300 h-full flex flex-col">
                <!-- Card Header -->
                <div class="p-6 border-b border-slate-100 dark:border-[#324d67] flex justify-between items-start">
                    <div class="flex flex-col gap-2">
                        <span class="text-[10px] font-bold text-[#137fec] uppercase tracking-widest px-2 py-0.5 bg-[#137fec]/10 rounded border border-[#137fec]/20 w-fit">#{{ $trx->kode_transaksi }}</span>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white leading-tight">{{ $trx->pelanggan->nama }}</h3>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route(auth()->user()->role . '.transaksi.struk', $trx->id) }}" class="p-2 text-[#137fec] hover:bg-[#137fec]/10 rounded-lg transition-colors" title="Nota">
                            <span class="material-symbols-outlined">receipt_long</span>
                        </a>
                        <a href="{{ route(auth()->user()->role . '.transaksi.label', $trx->id) }}" target="_blank" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white rounded-lg transition-colors" title="Label">
                            <span class="material-symbols-outlined">label</span>
                        </a>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-6 flex-1 space-y-4">
                    <div class="flex justify-between items-end">
                        <div class="text-[10px] text-slate-400 dark:text-[#92adc9] uppercase font-bold tracking-widest">Estimasi Selesai</div>
                        <div class="text-sm font-bold text-[#137fec]">{{ $trx->tanggal_estimasi->translatedFormat('d M, H:i') }}</div>
                    </div>

                    <div class="flex justify-between items-center text-sm pt-2">
                        <span class="text-slate-500 font-medium">Total Bayar:</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-white">Rp {{ number_format($trx->total, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @php
                            $statusPembayaranStyles = [
                                'lunas' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                'dp' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                'belum_bayar' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400'
                            ];
                            $statusPembayaranLabels = [
                                'lunas' => 'LUNAS',
                                'dp' => 'DP / PANJAR',
                                'belum_bayar' => 'BELUM BAYAR'
                            ];
                            $statusStyle = $statusPembayaranStyles[$trx->status_pembayaran] ?? $statusPembayaranStyles['belum_bayar'];
                            $statusLabel = $statusPembayaranLabels[$trx->status_pembayaran] ?? 'BELUM BAYAR';
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusStyle }}">
                            {{ $statusLabel }}
                        </span>
                        
                        @php
                            $statusOperasionalStyles = [
                                'selesai' => 'bg-[#137fec] text-white',
                                'diambil' => 'bg-slate-800 text-white',
                                'pending' => 'bg-slate-100 text-slate-500 dark:bg-[#233648] dark:text-[#92adc9]',
                                'proses' => 'bg-[#137fec]/20 text-[#137fec] border border-[#137fec]/20',
                                'cuci' => 'bg-blue-100 text-blue-700',
                                'setrika' => 'bg-indigo-100 text-indigo-700',
                                'packing' => 'bg-purple-100 text-purple-700',
                                'batal' => 'bg-red-100 text-red-700'
                            ];
                            $statusOperasionalLabels = [
                                'pending' => 'MENUNGGU',
                                'proses' => 'ANTRIAN',
                                'cuci' => 'CUCI',
                                'setrika' => 'SETRIKA',
                                'packing' => 'PACKING',
                                'selesai' => 'SIAP',
                                'diambil' => 'DIAMBIL',
                                'batal' => 'BATAL'
                            ];
                            $opStyle = $statusOperasionalStyles[$trx->status] ?? $statusOperasionalStyles['proses'];
                            $opLabel = $statusOperasionalLabels[$trx->status] ?? strtoupper($trx->status);
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $opStyle }}">
                            {{ $opLabel }}
                        </span>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-[#192633] border-t border-slate-100 dark:border-[#324d67] flex items-center justify-between transition-colors duration-200">
                    <button onclick="fetchLogs('{{ $trx->id }}')" class="text-[10px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest hover:text-[#137fec] transition-all flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">history</span> Catatan
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="px-4 py-2 bg-white dark:bg-[#233648] text-[#137fec] dark:text-white rounded-lg text-[10px] font-bold uppercase tracking-widest border border-slate-200 dark:border-[#324d67] hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                            Perbarui Status <span class="material-symbols-outlined text-xs">expand_more</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-[#111a22] rounded-xl shadow-2xl border border-slate-200 dark:border-[#233648] overflow-hidden z-50 transition-all" x-cloak>
                            <form action="{{ route(auth()->user()->role . '.transaksi.update-status', $trx->id) }}" method="POST">
                                @csrf @method('PATCH')
                                @php
                                    $nextStatuses = [
                                        'pending' => ['proses', 'batal'],
                                        'proses' => ['cuci', 'batal'],
                                        'cuci' => ['setrika'],
                                        'setrika' => ['packing'],
                                        'packing' => ['selesai'],
                                        'selesai' => ['diambil'],
                                        'diambil' => [],
                                        'batal' => []
                                    ];
                                    $available = $nextStatuses[$trx->status] ?? [];
                                @endphp
                                
                                @forelse($available as $s)
                                <button type="submit" name="status" value="{{ $s }}" class="w-full text-left px-5 py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-[#233648] border-b border-slate-50 dark:border-[#324d67] last:border-0 text-slate-700 dark:text-gray-300">
                                    Set Ke <span class="text-[#137fec]">{{ $statusOperasionalLabels[$s] ?? strtoupper($s) }}</span>
                                </button>
                                @empty
                                <div class="px-5 py-3 text-[10px] font-bold text-slate-400 italic uppercase tracking-widest text-center">Terkunci</div>
                                @endforelse
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 bg-white dark:bg-[#111a22] rounded-xl border-2 border-dashed border-slate-200 dark:border-[#324d67] flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-[#233648] mb-4">local_laundry_service</span>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white uppercase tracking-tight">Belum ada transaksi</h3>
            <p class="text-sm text-slate-400 dark:text-[#92adc9] mt-2 italic font-medium">Input order baru untuk memulai alur operasional.</p>
        </div>
        @endforelse
    </div>

    @if($transactions->hasPages())
    <div class="mt-8">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

<!-- Log Modal -->
<div id="logsModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="closeLogs()"></div>
    <div class="bg-white dark:bg-[#111a22] rounded-2xl p-6 w-full max-w-sm relative shadow-2xl border border-slate-200 dark:border-[#324d67]">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-tight" id="logTitle">Riwayat Proses</h4>
            <button onclick="closeLogs()" class="text-slate-400 hover:text-red-600 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div class="space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar" id="logsContent">
            <!-- Logs injected here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    async function fetchLogs(id) {
        showLoader();
        try {
            const response = await fetch(`{{ url(auth()->user()->role . '/transaksi') }}/${id}/json`);
            const data = await response.json();
            if(data.success) {
                const logs = data.transaksi.histories;
                const container = document.getElementById('logsContent');
                document.getElementById('logTitle').innerText = `Riwayat #${data.transaksi.kode_transaksi}`;
                container.innerHTML = logs.length ? '' : '<div class="text-center py-4 text-xs text-slate-400 italic font-medium">Belum ada riwayat proses</div>';
                
                logs.forEach((log, index) => {
                    container.innerHTML += `
                        <div class="flex gap-4 relative pb-4 last:pb-0">
                            ${index < logs.length - 1 ? '<div class="absolute left-[9px] top-6 bottom-0 w-[1px] bg-slate-100 dark:bg-[#324d67]"></div>' : ''}
                            <div class="h-5 w-5 rounded-full bg-[#137fec]/10 border-2 border-[#137fec] shrink-0 mt-0.5 z-10"></div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <p class="text-[10px] font-bold text-slate-800 dark:text-white uppercase tracking-widest">${log.status_baru}</p>
                                    <p class="text-[8px] text-slate-400 uppercase font-bold text-right ml-2">${new Date(log.created_at).toLocaleString('id-ID', {hour:'2-digit', minute:'2-digit', day:'2-digit', month:'short'})}</p>
                                </div>
                                <p class="text-[10px] text-slate-500 italic mt-0.5">PIC: <span class="font-bold text-[#137fec]">${log.user ? log.user.nama : 'Sistem'}</span></p>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('logsModal').classList.remove('hidden');
                document.getElementById('logsModal').classList.add('flex');
            }
        } catch(e) {
            console.error(e);
        } finally {
            hideLoader();
        }
    }

    function closeLogs() {
        document.getElementById('logsModal').classList.add('hidden');
        document.getElementById('logsModal').classList.remove('flex');
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('scannerComponent', () => ({
            code: '',
            scanner: null,
            isScanning: false,
            isProcessing: false,
            async handleScan(scannedCode = null) {
                if(this.isProcessing) return;
                const finalCode = scannedCode || this.code;
                if(finalCode.length < 5) return;
                
                this.isProcessing = true;
                try {
                    const response = await fetch(`{{ route(auth()->user()->role . '.transaksi.search-code') }}?code=${finalCode}`);
                    const result = await response.json();
                    
                    if(result.success) {
                        if(this.isScanning) this.stopScanner();
                        this.code = '';
                        
                        if(result.status === 'batal') {
                            Swal.fire({
                                title: 'BATAL!',
                                text: 'Transaksi ini sudah dibatalkan.',
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            }).then(() => { this.isProcessing = false; });
                            return;
                        }

                        if(result.status === 'selesai') {
                            const isLunas = result.status_pembayaran === 'lunas';
                            Swal.fire({
                                title: isLunas ? 'Siap Diambil!' : 'TAGIHAN BELUM LUNAS!',
                                html: `<div class='text-left space-y-2'>
                                    <p class='text-sm font-medium text-slate-600'>Pelanggan: <span class='font-bold text-slate-800'>${result.pelanggan}</span></p>
                                    <div class='p-4 ${isLunas ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100'} rounded-xl border text-xs leading-relaxed font-medium'>
                                        ${isLunas 
                                            ? 'Status LUNAS. Aman untuk diserahkan ke pelanggan.' 
                                            : 'BELUM LUNAS. Harap selesaikan pembayaran sebelum menyerahkan barang.'}
                                    </div>
                                </div>`,
                                icon: isLunas ? 'success' : 'warning',
                                showCancelButton: isLunas,
                                confirmButtonText: 'Konfirmasi Penyerahan',
                                cancelButtonText: 'Tutup',
                                confirmButtonColor: '#137fec',
                            }).then((res) => {
                                if(res.isConfirmed && isLunas) {
                                    this.updateToDiambil(result.id);
                                } else {
                                    this.isProcessing = false;
                                }
                            });
                            return;
                        }

                        Swal.fire({
                            title: 'Order Ditemukan!',
                            html: `<div class='text-left space-y-2'>
                                <p class='text-sm text-slate-600 font-medium'>Pelanggan: <span class='font-bold text-slate-800'>${result.pelanggan}</span></p>
                                <p class='text-xs font-bold text-[#137fec] uppercase tracking-widest'>STATUS: ${result.status}</p>
                            </div>`,
                            icon: 'success',
                            confirmButtonColor: '#137fec'
                        }).then(() => { this.isProcessing = false; });
                    } else {
                        this.isProcessing = false;
                    }
                } catch(e) {
                    this.isProcessing = false;
                }
            },
            async updateToDiambil(id) {
                try {
                    const response = await fetch(`{{ url(auth()->user()->role . '/transaksi') }}/${id}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: 'diambil' })
                    });
                    const result = await response.json();
                    if(result.success) {
                        Swal.fire({ title: 'Berhasil!', icon: 'success', timer: 1000, showConfirmButton: false }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal', result.message, 'error');
                        this.isProcessing = false;
                    }
                } catch(e) {
                    this.isProcessing = false;
                }
            },
            startScanner() {
                this.isScanning = true;
                this.scanner = new Html5Qrcode('reader');
                setTimeout(() => {
                    this.scanner.start({ facingMode: 'environment' }, { fps: 10, qrbox: 250 }, (txt) => this.handleScan(txt)).catch(err => {
                        this.isScanning = false;
                        Swal.fire('Error', 'Gagal akses kamera', 'error');
                    });
                }, 500);
            },
            stopScanner() {
                if(this.scanner) {
                    this.scanner.stop().then(() => { this.isScanning = false; this.scanner = null; });
                }
            }
        }))
    });
</script>
@endpush
