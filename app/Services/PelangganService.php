<?php

namespace App\Services;

use App\Models\Pelanggan;
use Illuminate\Pagination\LengthAwarePaginator;

class PelangganService
{
    /**
     * Get all customers with pagination.
     */
    public function getAllPelanggans(int $perPage = 12): LengthAwarePaginator
    {
        return Pelanggan::withCount(['transaksis as total_transaksi' => function ($query) {
                $query->where('status', '!=', 'batal');
            }])
            ->withSum(['transaksis as total_belanja' => function ($query) {
                $query->where('status', '!=', 'batal');
            }], 'total')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get a customer by ID.
     */
    public function getPelangganById(int $id): Pelanggan
    {
        return Pelanggan::findOrFail($id);
    }

    /**
     * Create a new customer.
     */
    public function createPelanggan(array $data): Pelanggan
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            if (empty($data['kode_pelanggan'])) {
                $data['kode_pelanggan'] = $this->generateKodePelanggan();
            }

            return Pelanggan::create($data);
        });
    }

    /**
     * Update an existing customer.
     */
    public function updatePelanggan(int $id, array $data): bool
    {
        $pelanggan = $this->getPelangganById($id);
        return $pelanggan->update($data);
    }

    /**
     * Delete a customer.
     */
    public function deletePelanggan(int $id): bool
    {
        $pelanggan = $this->getPelangganById($id);
        return $pelanggan->delete();
    }

    /**
     * Generate unique customer code.
     */
    private function generateKodePelanggan(): string
    {
        // Use lockForUpdate to prevent race condition on concurrent requests
        $lastPelanggan = Pelanggan::lockForUpdate()->latest()->first();
        $number = $lastPelanggan ? (int) substr($lastPelanggan->kode_pelanggan, 4) + 1 : 1;
        return 'PLG-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
