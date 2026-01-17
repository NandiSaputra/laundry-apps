<?php

namespace App\Services;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembayaran;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransaksiService
{
    /**
     * Create a new transaction with details and initial payment.
     */
    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();
            $kodeTransaksi = $this->generateKodeTransaksi();
            
            // Pre-fetch all layanan to avoid N+1 query
            $layananIds = collect($data['items'])->pluck('layanan_id')->unique();
            $layanans = Layanan::whereIn('id', $layananIds)->get()->keyBy('id');
            
            // Calculate totals and estimation
            $subtotal = 0;
            $maxEstimationHours = 0;
            
            foreach ($data['items'] as $item) {
                $layanan = $layanans->get($item['layanan_id']);
                if (!$layanan) {
                    throw new \Exception("Layanan dengan ID {$item['layanan_id']} tidak ditemukan.");
                }
                $subtotal += $layanan->harga * $item['jumlah'];
                
                if ($layanan->estimasi_jam > $maxEstimationHours) {
                    $maxEstimationHours = $layanan->estimasi_jam;
                }
            }
            
            $diskon = $data['diskon'] ?? 0;
            $total = $subtotal - $diskon;
            $totalDibayar = $data['jumlah_bayar'] ?? 0;
            
            $statusPembayaran = $totalDibayar >= $total ? 'lunas' : 'belum_bayar';

            // Create Transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'qr_code' => $this->generateQrCode($kodeTransaksi),
                'pelanggan_id' => $data['pelanggan_id'],
                'user_id' => $user->id,
                'tanggal_masuk' => now(),
                'tanggal_estimasi' => now()->addHours($maxEstimationHours),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
                'total_dibayar' => $totalDibayar,
                'status_pembayaran' => $statusPembayaran,
                'catatan' => $data['catatan'] ?? null,
                'parfum' => $data['parfum'] ?? null,
            ]);

            // Create Details (using pre-fetched layanans)
            foreach ($data['items'] as $item) {
                $layanan = $layanans->get($item['layanan_id']);
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'layanan_id' => $layanan->id,
                    'nama_layanan' => $layanan->nama,
                    'harga_satuan' => $layanan->harga,
                    'jumlah' => $item['jumlah'],
                    'satuan' => $layanan->satuan,
                    'subtotal' => $layanan->harga * $item['jumlah'],
                    'catatan' => $item['catatan'] ?? null,
                ]);
            }

            // Create Payment if any
            if ($totalDibayar > 0) {
                // BUG FIX: Payment amount recorded should not exceed transaction total.
                // If customer pays 10k for 7k bill, income is 7k, not 10k. 3k is change.
                $recordedAmount = ($totalDibayar >= $total) ? $total : $totalDibayar;

                Pembayaran::create([
                    'transaksi_id' => $transaksi->id,
                    'user_id' => $user->id,
                    'kode_pembayaran' => 'PAY-' . strtoupper(Str::random(8)),
                    'jumlah' => $recordedAmount,
                    'metode' => $data['metode_pembayaran'] ?? 'tunai',
                    'tanggal_bayar' => now(),
                ]);
            }

            // LOG INITIAL STATUS
            \App\Models\StatusTransaksiHistory::create([
                'transaksi_id' => $transaksi->id,
                'status_lama' => null,
                'status_baru' => 'pending',
                'user_id' => $user->id,
            ]);

            return $transaksi;
        });
    }

    /**
     * Update transaction status and handle logic transitions.
     */
    public function updateStatus(int $id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {
            $transaksi = Transaksi::lockForUpdate()->findOrFail($id);
            
            // STATE GUARD: Prevent changes if already 'batal' or 'diambil'
            if (in_array($transaksi->status, ['batal', 'diambil'])) {
                throw new \Exception("Status '" . $transaksi->status . "' sudah final dan tidak bisa diubah.");
            }

            // IDEMPOTENCY: If the next status is already the current status, just return (already updated by another process/employee)
            if ($transaksi->status === $status) {
                return $transaksi;
            }

            // STATE GUARD: Only allow sequential or logical updates
            // (Optional: add more specific logic if needed)

            $data = ['status' => $status];
            
            if ($status === 'selesai') {
                $data['tanggal_selesai'] = now();
            } elseif ($status === 'diambil') {
                $data['tanggal_diambil'] = now();
                
                // SECURITY: Block handover if not lunas
                if ($transaksi->status_pembayaran !== 'lunas') {
                    throw new \Exception("Order belum lunas! Tidak bisa diserahkan.");
                }
            }
            
            $statusLama = $transaksi->status;
            $transaksi->update($data);

            // LOG HISTORY
            \App\Models\StatusTransaksiHistory::create([
                'transaksi_id' => $transaksi->id,
                'status_lama' => $statusLama,
                'status_baru' => $status,
                'user_id' => Auth::id(),
            ]);

            return $transaksi;
        });
    }


    /**
     * Generate a unique transaction code.
     */
    private function generateKodeTransaksi()
    {
        $prefix = 'TRX-' . date('Ymd');
        // Use lockForUpdate to prevent race condition on concurrent requests
        $lastTransaksi = Transaksi::where('kode_transaksi', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = (int) substr($lastTransaksi->kode_transaksi, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $newNumber;
    }

    /**
     * Generate a dummy QR code string for now.
     */
    private function generateQrCode($kode)
    {
        return $kode;
    }
}
