<?php

namespace App\Services;

use App\Models\Layanan;
use Illuminate\Pagination\LengthAwarePaginator;

class LayananService
{
    /**
     * Get all services with pagination.
     */
    public function getAllLayanans(int $perPage = 12): LengthAwarePaginator
    {
        return Layanan::with('kategori')->latest()->paginate($perPage);
    }

    /**
     * Get a service by its ID.
     */
    public function getLayananById(int $id): Layanan
    {
        return Layanan::with('kategori')->findOrFail($id);
    }

    /**
     * Store a new service.
     */
    public function createLayanan(array $data): Layanan
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // Generate kode_layanan if not provided (e.g. LYN-001)
            if (!isset($data['kode_layanan'])) {
                // Use lockForUpdate to prevent race condition on concurrent requests
                $lastLayanan = Layanan::lockForUpdate()->latest()->first();
                $number = $lastLayanan ? (int) substr($lastLayanan->kode_layanan, 4) + 1 : 1;
                $data['kode_layanan'] = 'LYN-' . str_pad($number, 3, '0', STR_PAD_LEFT);
            }

            return Layanan::create($data);
        });
    }

    /**
     * Update an existing service.
     */
    public function updateLayanan(int $id, array $data): bool
    {
        $layanan = $this->getLayananById($id);
        return $layanan->update($data);
    }

    /**
     * Delete a service.
     */
    public function deleteLayanan(int $id): bool
    {
        $layanan = $this->getLayananById($id);
        return $layanan->delete();
    }
}
