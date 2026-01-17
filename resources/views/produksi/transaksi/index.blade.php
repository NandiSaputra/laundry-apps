@extends('layouts.admin')

@section('title', 'Daftar Antrian Produksi | ' . ($shopSettings['shop_name'] ?? 'LaundryBiz'))
@section('page-title', 'Daftar Antrian Produksi')

@section('content')
<div class="space-y-6">
    <!-- Header Summary -->
    <div class="bg-white dark:bg-[#111a22] p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm flex flex-col md:flex-row justify-between items-center gap-4 sm:gap-6 transition-colors duration-200">
        <div class="text-center md:text-left">
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-white uppercase tracking-tight">Antrian Produksi</h2>
            <p class="text-[10px] sm:text-xs text-slate-500 dark:text-[#92adc9] mt-1 font-medium italic">Monitor & perbarui status cucian secara real-time.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button onclick="openScanner()" class="w-full md:w-auto px-5 sm:px-6 py-3 bg-[#137fec] text-white rounded-xl text-[10px] sm:text-xs font-black hover:bg-[#137fec]/90 shadow-lg shadow-[#137fec]/20 transition-all flex items-center justify-center gap-2 uppercase tracking-widest active:scale-95">
                <span class="material-symbols-outlined text-lg">qr_code_scanner</span>
                <span>Scan Nota QR</span>
            </button>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-[#111a22] p-4 sm:p-6 rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm transition-colors duration-200">
        <form action="{{ route('produksi.transaksi.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3 sm:gap-4">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-[#92adc9] text-lg">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode atau Pelanggan..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-[#233648] border-none rounded-lg text-[13px] focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all font-medium">
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <select name="status" class="flex-1 bg-slate-50 dark:bg-[#233648] border-none rounded-lg px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-[#137fec] outline-none text-slate-800 dark:text-white transition-all">
                    <option value="">Semua Antrian</option>
                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Diproses</option>
                    <option value="cuci" {{ request('status') == 'cuci' ? 'selected' : '' }}>Pencucian</option>
                    <option value="setrika" {{ request('status') == 'setrika' ? 'selected' : '' }}>Setrika</option>
                    <option value="packing" {{ request('status') == 'packing' ? 'selected' : '' }}>Packing</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-800 dark:bg-[#2d445b] text-white rounded-lg text-[10px] font-bold hover:bg-slate-900 transition-all uppercase tracking-[0.2em] active:scale-95">Filter</button>
            </div>
        </form>
    </div>

    <!-- Transaction Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($transactions as $trx)
        <div class="h-full">
            <div class="bg-white dark:bg-[#111a22] rounded-xl border border-slate-200 dark:border-[#324d67] shadow-sm overflow-hidden group hover:border-[#137fec] hover:shadow-md transition-all duration-300 h-full flex flex-col">
                <!-- Card Header -->
                <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-[#324d67] flex justify-between items-start bg-slate-50/50 dark:bg-white/5">
                    <div class="flex flex-col gap-1.5">
                        <span class="text-[9px] font-bold text-[#137fec] uppercase tracking-widest px-2 py-0.5 bg-[#137fec]/10 rounded border border-[#137fec]/20 w-fit">#{{ $trx->kode_transaksi }}</span>
                        <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white tracking-tight">{{ $trx->pelanggan->nama }}</h3>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('produksi.transaksi.label', $trx->id) }}" target="_blank" class="p-1.5 sm:p-2 text-slate-400 hover:text-[#137fec] rounded-lg transition-colors border border-transparent hover:border-slate-100" title="Cetak Label Item">
                            <span class="material-symbols-outlined text-xl sm:text-2xl">label</span>
                        </a>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-4 sm:p-6 flex-1 space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="text-[8px] sm:text-[9px] text-slate-400 dark:text-[#92adc9] uppercase font-bold tracking-[0.2em]">Estimasi</div>
                        @php $isOverdue = $trx->tanggal_estimasi->isPast() && $trx->status != 'selesai'; @endphp
                        <div class="text-[11px] sm:text-xs font-bold {{ $isOverdue ? 'text-red-500 animate-pulse' : 'text-[#137fec]' }} text-right leading-tight">
                            {{ $trx->tanggal_estimasi->translatedFormat('d M, H:i') }}
                            <span class="text-[8px] block font-medium opacity-70">({{ $trx->tanggal_estimasi->diffForHumans() }})</span>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-[#233648] rounded-lg border border-slate-100 dark:border-[#324d67]">
                        <div class="text-[8px] sm:text-[9px] text-slate-400 dark:text-[#92adc9] uppercase font-bold tracking-widest mb-2">Item Cucian:</div>
                        <div class="space-y-1.5">
                            @foreach($trx->details as $detail)
                            <div class="flex justify-between text-[11px] sm:text-xs font-medium text-slate-700 dark:text-slate-300">
                                <span class="truncate pr-2">{{ $detail->layanan->nama }}</span>
                                <span class="font-black shrink-0">{{ rtrim(rtrim($detail->jumlah, '0'), '.') }} {{ $detail->layanan->satuan }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @php
                            $statusOperasionalStyles = [
                                'selesai' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 border border-green-200/50',
                                'pending' => 'bg-slate-100 text-slate-500 dark:bg-[#233648] dark:text-[#92adc9] border border-slate-200/50',
                                'proses'  => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 border border-blue-200/50',
                                'cuci'    => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-400 border border-cyan-200/50',
                                'setrika' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200/50',
                                'packing' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400 border border-purple-200/50',
                            ];
                            $opStyle = $statusOperasionalStyles[$trx->status] ?? 'bg-slate-100 text-slate-500';
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-widest {{ $opStyle }}">
                            {{ str_replace('_', ' ', $trx->status) }}
                        </span>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="px-4 sm:px-6 py-4 bg-slate-50 dark:bg-[#192633] border-t border-slate-100 dark:border-[#324d67] flex items-center justify-between transition-colors duration-200">
                    <button onclick="fetchLogs('{{ $trx->id }}')" class="text-[9px] font-bold text-slate-400 dark:text-[#5a7690] uppercase tracking-widest hover:text-[#137fec] transition-all flex items-center gap-1 active:scale-95">
                        <span class="material-symbols-outlined text-xs">history</span> Log
                    </button>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="px-3 sm:px-4 py-2 bg-white dark:bg-[#233648] text-[#137fec] dark:text-white rounded-lg text-[10px] sm:text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-[#324d67] hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm active:scale-95">
                            Status <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-[#111a22] rounded-xl shadow-2xl border border-slate-200 dark:border-[#233648] overflow-hidden z-50 transition-all" x-cloak>
                            <form action="{{ route('produksi.transaksi.update-status', $trx->id) }}" method="POST">
                                @csrf @method('PATCH')
                                @php
                                    $nextStatuses = [
                                        'pending' => ['proses'],
                                        'proses' => ['cuci'],
                                        'cuci' => ['setrika'],
                                        'setrika' => ['packing'],
                                        'packing' => ['selesai'],
                                        'selesai' => [],
                                        'diambil' => [],
                                        'batal' => []
                                    ];
                                    $available = $nextStatuses[$trx->status] ?? [];
                                @endphp
                                
                                @forelse($available as $s)
                                <button type="submit" name="status" value="{{ $s }}" class="w-full text-left px-5 py-3 text-[10px] font-bold uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-[#233648] border-b border-slate-50 dark:border-[#324d67] last:border-0 text-slate-700 dark:text-gray-300 active:bg-slate-100">
                                    Pindahkan ke <span class="text-[#137fec]">{{ str_replace('_', ' ', $s) }}</span>
                                </button>
                                @empty
                                <div class="px-5 py-3 text-[10px] font-bold text-slate-400 italic uppercase tracking-widest text-center">Produksi Selesai</div>
                                @endforelse
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 bg-white dark:bg-[#111a22] rounded-xl border-2 border-dashed border-slate-200 dark:border-[#324d67] flex flex-col items-center justify-center text-center">
            <span class="material-symbols-outlined text-6xl text-slate-200 dark:text-[#233648] mb-4">task</span>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white uppercase tracking-tight">Antrian Kosong</h3>
            <p class="text-sm text-slate-400 dark:text-[#92adc9] mt-2 italic font-medium">Bagus! Semua cucian sudah diproses.</p>
        </div>
        @endforelse
    </div>

    @if($transactions->hasPages())
    <div class="mt-8">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

<!-- QR Scanner Modal -->
<div id="scannerModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-2 sm:p-4 bg-slate-900/80 backdrop-blur-md">
    <div class="fixed inset-0" onclick="closeScanner()"></div>
    <div class="bg-white dark:bg-[#111a22] rounded-3xl sm:rounded-[2.5rem] p-6 sm:p-8 w-full max-w-lg relative shadow-2xl border border-slate-200 dark:border-[#324d67] overflow-hidden">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <span class="material-symbols-outlined text-white">qr_code_scanner</span>
                </div>
                <h4 class="font-black text-slate-800 dark:text-white text-xs sm:text-sm uppercase tracking-widest italic">Pindai QR Nota</h4>
            </div>
            <button onclick="closeScanner()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-[#233648] text-slate-400 hover:text-red-600 transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <div id="reader" class="rounded-xl sm:rounded-2xl overflow-hidden border-2 sm:border-4 border-slate-100 dark:border-[#233648] bg-slate-50 dark:bg-[#192633]"></div>
        
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-500/10 rounded-2xl border border-blue-100 dark:border-blue-500/20">
            <p class="text-[9px] sm:text-[10px] text-blue-600 dark:text-blue-400 font-bold uppercase tracking-widest text-center leading-relaxed italic">
                Arahkan kamera ke QR Code di struk untuk memproses item.
            </p>
        </div>
    </div>
</div>

<!-- QR Quick Action Modal -->
<div id="qrActionModal" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-2 sm:p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="closeQrAction()"></div>
    <div class="bg-white dark:bg-[#111a22] rounded-3xl sm:rounded-[3rem] p-0 w-full max-w-md relative shadow-2xl border border-slate-200 dark:border-[#324d67] overflow-hidden">
        <div class="bg-[#137fec] p-6 sm:p-8 text-white relative overflow-hidden">
             <!-- Decorative blob -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <span id="qrOrderCode" class="text-[8px] sm:text-[10px] font-black uppercase tracking-[0.2em] opacity-80 italic">#TRX-00000000</span>
                    <h4 id="qrCustomerName" class="text-xl sm:text-2xl font-black tracking-tighter mt-1 italic uppercase">Nama Pelanggan</h4>
                </div>
                <button onclick="closeQrAction()" class="text-white/60 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl sm:text-3xl">close</span>
                </button>
            </div>
        </div>
        
        <div class="p-6 sm:p-8">
            <div class="space-y-6">
                <!-- Item List Summary -->
                <div class="p-4 bg-slate-50 dark:bg-[#192633] rounded-2xl border border-slate-100 dark:border-[#233648]">
                    <div class="text-[8px] sm:text-[9px] text-slate-400 dark:text-[#5a7690] uppercase font-black tracking-[0.2em] mb-3 italic">Item Cucian:</div>
                    <div id="qrItemList" class="space-y-2">
                        <!-- Injected items -->
                    </div>
                </div>

                <!-- Current Status -->
                <div class="flex justify-between items-center px-2">
                    <span class="text-[9px] sm:text-[10px] text-slate-400 font-bold uppercase tracking-widest">Status Saat Ini</span>
                    <span id="qrCurrentStatus" class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-[8px] sm:text-[9px] font-black uppercase tracking-widest italic">PROSES</span>
                </div>

                <div class="h-px bg-slate-100 dark:bg-[#324d67]"></div>

                <!-- Action Buttons -->
                <div>
                    <h5 class="text-[9px] sm:text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4 italic px-2">Langkah Berikutnya:</h5>
                    <div id="qrNextActions" class="space-y-3">
                        <!-- Injected action buttons -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Modal -->
<div id="logsModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="closeLogs()"></div>
    <div class="bg-white dark:bg-[#111a22] rounded-2xl p-6 w-full max-w-sm relative shadow-2xl border border-slate-200 dark:border-[#324d67]">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-bold text-slate-800 dark:text-white text-[11px] uppercase tracking-widest" id="logTitle">Log Aktivitas</h4>
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
    let html5QrCode;

    function openScanner() {
        document.getElementById('scannerModal').classList.remove('hidden');
        document.getElementById('scannerModal').classList.add('flex');
        
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess);
    }

    function closeScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                document.getElementById('scannerModal').classList.add('hidden');
                document.getElementById('scannerModal').classList.remove('flex');
            });
        } else {
            document.getElementById('scannerModal').classList.add('hidden');
            document.getElementById('scannerModal').classList.remove('flex');
        }
    }

    async function onScanSuccess(decodedText, decodedResult) {
        // decodedText is the QR code content (The code like TRX-123456)
        closeScanner();
        showLoader();
        
        try {
            // First search by code to get the ID
            const searchResponse = await fetch(`{{ route('produksi.transaksi.search-code') }}?code=${decodedText}`);
            const searchData = await searchResponse.json();
            
            if (searchData.success) {
                // If found, fetch full details using the ID
                fetchOrderForQrAction(searchData.id);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Kode transaksi tidak valid atau tidak ditemukan.',
                });
            }
        } catch (e) {
            console.error(e);
        } finally {
            hideLoader();
        }
    }

    async function fetchOrderForQrAction(id) {
        showLoader();
        try {
            const response = await fetch(`{{ url('produksi/transaksi') }}/${id}/json`);
            const data = await response.json();
            
            if(data.success) {
                const trx = data.transaksi;
                const nextStatuses = data.next_available_statuses;
                
                document.getElementById('qrOrderCode').innerText = `#${trx.kode_transaksi}`;
                document.getElementById('qrCustomerName').innerText = trx.pelanggan.nama;
                document.getElementById('qrCurrentStatus').innerText = trx.status.toUpperCase();
                
                // Items
                const itemContainer = document.getElementById('qrItemList');
                itemContainer.innerHTML = '';
                trx.details.forEach(item => {
                    itemContainer.innerHTML += `
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-600 font-bold italic uppercase">${item.layanan.nama}</span>
                            <span class="px-2 py-0.5 bg-white dark:bg-[#233648] rounded text-slate-800 dark:text-white font-black">${item.jumlah} ${item.layanan.satuan}</span>
                        </div>
                    `;
                });

                // Next Actions
                const actionContainer = document.getElementById('qrNextActions');
                actionContainer.innerHTML = '';
                
                if (nextStatuses.length === 0) {
                    actionContainer.innerHTML = `
                        <div class="p-4 bg-slate-50 dark:bg-[#192633] rounded-2xl border border-dashed border-slate-200 dark:border-[#324d67] text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Produksi Selesai / Siap Diambil</p>
                        </div>
                    `;
                } else {
                    nextStatuses.forEach(status => {
                        const statusColors = {
                            'proses': 'bg-blue-600 shadow-blue-200',
                            'cuci': 'bg-cyan-600 shadow-cyan-200',
                            'setrika': 'bg-amber-500 shadow-amber-200',
                            'packing': 'bg-purple-600 shadow-purple-200',
                            'selesai': 'bg-green-600 shadow-green-200'
                        };
                        const color = statusColors[status] || 'bg-slate-800';
                        
                        actionContainer.innerHTML += `
                            <button onclick="updateStatusFromQr('${trx.id}', '${status}')" class="w-full py-4 ${color} text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                                <span>Pindahkan ke ${status.toUpperCase()}</span>
                                <span class="material-symbols-outlined text-lg">arrow_forward</span>
                            </button>
                        `;
                    });
                }

                document.getElementById('qrActionModal').classList.remove('hidden');
                document.getElementById('qrActionModal').classList.add('flex');
            }
        } catch(e) {
            console.error(e);
        } finally {
            hideLoader();
        }
    }

    async function updateStatusFromQr(id, status) {
        showLoader();
        try {
            // Fix URI mismatch: use status instead of update-status
            const url = `{{ url('produksi/transaksi') }}/${id}/status`;
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            });
            const data = await response.json();
            
            if(data.success) {
                closeQrAction();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        } catch(e) {
            console.error(e);
        } finally {
            hideLoader();
        }
    }

    function closeQrAction() {
        document.getElementById('qrActionModal').classList.add('hidden');
        document.getElementById('qrActionModal').classList.remove('flex');
    }

    async function fetchLogs(id) {
        showLoader();
        try {
            const response = await fetch(`{{ url('produksi/transaksi') }}/${id}/json`);
            const data = await response.json();
            if(data.success) {
                const logs = data.transaksi.histories;
                const container = document.getElementById('logsContent');
                document.getElementById('logTitle').innerText = `Riwayat #${data.transaksi.kode_transaksi}`;
                container.innerHTML = logs.length ? '' : '<div class="text-center py-4 text-xs text-slate-400 italic">Belum ada riwayat</div>';
                
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
                                <p class="text-[10px] text-slate-500 italic mt-0.5">Oleh: <span class="font-bold text-[#137fec]">${log.user ? log.user.nama : 'Sistem'}</span></p>
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
</script>
@endpush
