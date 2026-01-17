<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembayaran;
use App\Models\StatusTransaksiHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Settings
        $this->createSettings();
        
        // Create Categories
        $this->createKategoris();
        
        // Create Services
        $this->createLayanans();
        
        // Create Customers
        $this->createPelanggans();
        
        // Create Demo Transactions
        // $this->createTransaksis();
    }

    private function createSettings(): void
    {
        $settings = [
            'shop_name' => 'LaundryBiz',
            'shop_address' => 'Jl. Laundry No. 123, Jakarta',
            'shop_phone' => '021-1234567',
            'shop_email' => 'info@laundrybiz.com',
            'shop_logo' => null,
            'receipt_footer' => 'Terima kasih atas kepercayaan Anda!',
            'whatsapp_number' => '6281234567890',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function createKategoris(): void
    {
        $kategoris = [
            ['nama' => 'Reguler', 'deskripsi' => 'Layanan cuci standar 2-3 hari'],
            ['nama' => 'Express', 'deskripsi' => 'Layanan cuci cepat 1 hari'],
            ['nama' => 'Premium', 'deskripsi' => 'Layanan premium dengan perawatan khusus'],
            ['nama' => 'Dry Clean', 'deskripsi' => 'Layanan dry cleaning untuk pakaian khusus'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(['nama' => $kategori['nama']], $kategori);
        }
    }

    private function createLayanans(): void
    {
        $layanans = [
            // Reguler
            ['kategori_id' => 1, 'kode_layanan' => 'LYN-001', 'nama' => 'Cuci Lipat Reguler', 'harga' => 7000, 'satuan' => 'kg', 'estimasi_jam' => 48, 'is_active' => true],
            ['kategori_id' => 1, 'kode_layanan' => 'LYN-002', 'nama' => 'Cuci Setrika Reguler', 'harga' => 10000, 'satuan' => 'kg', 'estimasi_jam' => 48, 'is_active' => true],
            ['kategori_id' => 1, 'kode_layanan' => 'LYN-003', 'nama' => 'Setrika Saja', 'harga' => 5000, 'satuan' => 'kg', 'estimasi_jam' => 24, 'is_active' => true],
            
            // Express
            ['kategori_id' => 2, 'kode_layanan' => 'LYN-004', 'nama' => 'Cuci Lipat Express', 'harga' => 12000, 'satuan' => 'kg', 'estimasi_jam' => 12, 'is_active' => true],
            ['kategori_id' => 2, 'kode_layanan' => 'LYN-005', 'nama' => 'Cuci Setrika Express', 'harga' => 15000, 'satuan' => 'kg', 'estimasi_jam' => 12, 'is_active' => true],
            
            // Premium
            ['kategori_id' => 3, 'kode_layanan' => 'LYN-006', 'nama' => 'Cuci Bed Cover', 'harga' => 35000, 'satuan' => 'pcs', 'estimasi_jam' => 72, 'is_active' => true],
            ['kategori_id' => 3, 'kode_layanan' => 'LYN-007', 'nama' => 'Cuci Selimut', 'harga' => 25000, 'satuan' => 'pcs', 'estimasi_jam' => 48, 'is_active' => true],
            ['kategori_id' => 3, 'kode_layanan' => 'LYN-008', 'nama' => 'Cuci Karpet', 'harga' => 30000, 'satuan' => 'm2', 'estimasi_jam' => 72, 'is_active' => true],
            
            // Dry Clean
            ['kategori_id' => 4, 'kode_layanan' => 'LYN-009', 'nama' => 'Dry Clean Jas', 'harga' => 50000, 'satuan' => 'pcs', 'estimasi_jam' => 72, 'is_active' => true],
            ['kategori_id' => 4, 'kode_layanan' => 'LYN-010', 'nama' => 'Dry Clean Gaun', 'harga' => 75000, 'satuan' => 'pcs', 'estimasi_jam' => 72, 'is_active' => true],
        ];

        foreach ($layanans as $layanan) {
            Layanan::updateOrCreate(['kode_layanan' => $layanan['kode_layanan']], $layanan);
        }
    }

    private function createPelanggans(): void
    {
        $pelanggans = [
            ['kode_pelanggan' => 'PLG-0001', 'nama' => 'Budi Santoso', 'telepon' => '081234567001', 'alamat' => 'Jl. Merdeka No. 10', 'email' => 'budi@email.com'],
            ['kode_pelanggan' => 'PLG-0002', 'nama' => 'Siti Rahayu', 'telepon' => '081234567002', 'alamat' => 'Jl. Sudirman No. 25', 'email' => 'siti@email.com'],
            ['kode_pelanggan' => 'PLG-0003', 'nama' => 'Ahmad Fauzi', 'telepon' => '081234567003', 'alamat' => 'Jl. Gatot Subroto No. 5', 'email' => 'ahmad@email.com'],
            ['kode_pelanggan' => 'PLG-0004', 'nama' => 'Dewi Lestari', 'telepon' => '081234567004', 'alamat' => 'Jl. Asia Afrika No. 15', 'email' => 'dewi@email.com'],
            ['kode_pelanggan' => 'PLG-0005', 'nama' => 'Rudi Hermawan', 'telepon' => '081234567005', 'alamat' => 'Jl. Diponegoro No. 30', 'email' => 'rudi@email.com'],
            ['kode_pelanggan' => 'PLG-0006', 'nama' => 'Nina Wahyuni', 'telepon' => '081234567006', 'alamat' => 'Jl. Imam Bonjol No. 8', 'email' => null],
            ['kode_pelanggan' => 'PLG-0007', 'nama' => 'Hendra Wijaya', 'telepon' => '081234567007', 'alamat' => 'Jl. Veteran No. 12', 'email' => 'hendra@email.com'],
            ['kode_pelanggan' => 'PLG-0008', 'nama' => 'Rina Susanti', 'telepon' => '081234567008', 'alamat' => 'Jl. Pahlawan No. 20', 'email' => null],
        ];

        foreach ($pelanggans as $pelanggan) {
            Pelanggan::updateOrCreate(['kode_pelanggan' => $pelanggan['kode_pelanggan']], $pelanggan);
        }
    }

    private function createTransaksis(): void
    {
        $admin = User::where('role', 'admin')->first();
        $kasir = User::where('role', 'kasir')->first();
        
        if (!$admin && !$kasir) return;
        
        $user = $kasir ?? $admin;
        
        $statuses = ['pending', 'proses', 'cuci', 'setrika', 'packing', 'selesai', 'diambil'];
        
        // Create 15 sample transactions
        for ($i = 1; $i <= 15; $i++) {
            $pelangganId = rand(1, 8);
            $status = $statuses[array_rand($statuses)];
            $daysAgo = rand(0, 14);
            $tanggalMasuk = Carbon::now()->subDays($daysAgo);
            
            $transaksi = Transaksi::create([
                'kode_transaksi' => 'TRX-' . $tanggalMasuk->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'qr_code' => 'TRX-' . $tanggalMasuk->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'pelanggan_id' => $pelangganId,
                'user_id' => $user->id,
                'tanggal_masuk' => $tanggalMasuk,
                'tanggal_estimasi' => $tanggalMasuk->copy()->addHours(48),
                'tanggal_selesai' => in_array($status, ['selesai', 'diambil']) ? $tanggalMasuk->copy()->addDays(2) : null,
                'tanggal_diambil' => $status === 'diambil' ? $tanggalMasuk->copy()->addDays(3) : null,
                'status' => $status,
                'subtotal' => 0,
                'diskon' => 0,
                'total' => 0,
                'total_dibayar' => 0,
                'status_pembayaran' => 'belum_bayar',
                'catatan' => null,
                'parfum' => ['Lavender', 'Rose', 'Fresh', null][rand(0, 3)],
            ]);
            
            // Add 1-3 items per transaction
            $subtotal = 0;
            $itemCount = rand(1, 3);
            
            for ($j = 0; $j < $itemCount; $j++) {
                $layanan = Layanan::find(rand(1, 10));
                if (!$layanan) continue;
                
                $jumlah = rand(1, 5);
                $itemSubtotal = $layanan->harga * $jumlah;
                $subtotal += $itemSubtotal;
                
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'layanan_id' => $layanan->id,
                    'nama_layanan' => $layanan->nama,
                    'harga_satuan' => $layanan->harga,
                    'jumlah' => $jumlah,
                    'satuan' => $layanan->satuan,
                    'subtotal' => $itemSubtotal,
                ]);
            }
            
            // Update totals
            $diskon = $subtotal > 100000 ? 10000 : 0;
            $total = $subtotal - $diskon;
            $isLunas = in_array($status, ['selesai', 'diambil']) || rand(0, 1);
            
            $transaksi->update([
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'total' => $total,
                'total_dibayar' => $isLunas ? $total : 0,
                'status_pembayaran' => $isLunas ? 'lunas' : 'belum_bayar',
            ]);
            
            // Create payment if lunas
            if ($isLunas) {
                Pembayaran::create([
                    'transaksi_id' => $transaksi->id,
                    'user_id' => $user->id,
                    'kode_pembayaran' => 'PAY-' . strtoupper(Str::random(8)),
                    'jumlah' => $total,
                    'metode' => ['tunai', 'transfer', 'qris'][rand(0, 2)],
                    'tanggal_bayar' => $tanggalMasuk,
                ]);
            }
            
            // Create status history
            StatusTransaksiHistory::create([
                'transaksi_id' => $transaksi->id,
                'status_lama' => null,
                'status_baru' => 'pending',
                'user_id' => $user->id,
            ]);
        }
    }
}
