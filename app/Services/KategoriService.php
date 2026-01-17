<?php

namespace App\Services;

use App\Models\Kategori;
use Illuminate\Support\Collection;

class KategoriService
{
    /**
     * Get all categories with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllCategories(int $perPage = 12)
    {
        return Kategori::latest()->paginate($perPage);
    }

    /**
     * Get a specific category by ID.
     *
     * @param int $id
     * @return Kategori
     */
    public function getCategoryById(int $id): Kategori
    {
        return Kategori::findOrFail($id);
    }

    /**
     * Store a new category.
     *
     * @param array $data
     * @return Kategori
     */
    public function createCategory(array $data): Kategori
    {
        return Kategori::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCategory(int $id, array $data): bool
    {
        $category = $this->getCategoryById($id);
        return $category->update($data);
    }

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteCategory(int $id): ?bool
    {
        $category = $this->getCategoryById($id);
        return $category->delete();
    }
}
