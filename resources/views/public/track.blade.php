<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking #{{ $transaksi->kode_transaksi }} - LaundryApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 7.5px;
            top: 24px;
            bottom: -8px;
            width: 2px;
            background-color: #e2e8f0;
        }
        .timeline-item:last-child::before { display: none; }
    </style>
</head>
<body class="min-h-screen py-10">
    <div class="max-w-2xl mx-auto px-4">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow-sm border border-slate-200 text-slate-600 hover:text-blue-600 transition-all">
                <i class="ph-bold ph-arrow-left"></i>
                <span class="text-sm font-bold">Kembali</span>
            </a>
            <div class="text-right">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status Pesanan</span>
                <div class="text-lg font-extrabold text-blue-600">{{ strtoupper($transaksi->status) }}</div>
            </div>
        </div>

        {{-- Order Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 overflow-hidden mb-6 border border-slate-100">
            <div class="bg-blue-600 p-6 text-white text-center">
                <div class="text-xs opacity-80 mb-1">Kode Transaksi</div>
                <div class="text-2xl font-black tracking-tighter">{{ $transaksi->kode_transaksi }}</div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase mb-1">Pelanggan</div>
                        <div class="font-bold text-slate-900">{{ $transaksi->pelanggan->nama }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-400 uppercase mb-1">Estimasi Selesai</div>
                        <div class="font-bold text-slate-900">{{ $transaksi->tanggal_estimasi->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                        <i class="ph-bold ph-list-bullets text-blue-600"></i>
                        Detail Cucian
                    </h3>
                    <div class="space-y-3">
                        @foreach($transaksi->details as $item)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 font-medium">{{ $item->jumlah }} {{ $item->satuan }} {{ $item->nama_layanan }}</span>
                            <span class="text-slate-400">#{{ $loop->iteration }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 p-8 border border-slate-100">
            <h3 class="text-sm font-black text-slate-900 mb-8 flex items-center gap-2 uppercase tracking-wide">
                <i class="ph-bold ph-rocket-launch text-blue-600"></i>
                Timeline Proses
            </h3>

            <div class="space-y-6">
                {{-- Step 1: Pesanan Masuk (Sama dengan created_at) --}}
                <div class="timeline-item relative flex gap-6">
                    <div class="z-10 w-4 h-4 rounded-full bg-blue-600 ring-4 ring-blue-50 mt-1"></div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">Pesanan Diterima</div>
                        <div class="text-xs text-slate-400 font-medium">{{ $transaksi->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                {{-- Status History --}}
                @foreach($transaksi->histories as $history)
                <div class="timeline-item relative flex gap-6">
                    <div class="z-10 w-4 h-4 rounded-full bg-blue-600 ring-4 ring-blue-50 mt-1"></div>
                    <div>
                        <div class="text-sm font-bold text-slate-900">
                            {{-- Mapping status ke label bahasa indo --}}
                            @php
                                $statusLabels = [
                                    'Proses' => 'Mulai Diproses',
                                    'Cuci' => 'Sedang Dicuci',
                                    'Setrika' => 'Sedang Disetrika',
                                    'Selesai' => 'Pesanan Selesai (Siap Diambil)',
                                    'Diambil' => 'Pesanan Sudah Diserahkan',
                                    'Batal' => 'Pesanan Dibatalkan'
                                ];
                                $label = $statusLabels[$history->status_baru] ?? 'Update Status: ' . $history->status_baru;
                            @endphp
                            {{ $label }}
                        </div>
                        <div class="text-xs text-slate-400 font-medium">
                            {{ $history->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs text-slate-400 font-medium px-10 leading-relaxed">
                Butuh bantuan? Hubungi WhatsApp kami di 0812-xxxx-xxxx dengan menyebutkan kode transaksi di atas.
            </p>
        </div>
    </div>
</body>
</html>
