<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Kategori\StoreKategoriRequest;
use App\Http\Requests\Admin\Kategori\UpdateKategoriRequest;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class kategoriController extends Controller
{
    protected $kategoriService;

    public function __construct(KategoriService $kategoriService)
    {
        $this->kategoriService = $kategoriService;
    }

    public function index()
    {
        $categories = $this->kategoriService->getAllCategories();
        return view('admin.kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.kategori.create-kategori');
    }

    public function store(StoreKategoriRequest $request)
    {
        $this->kategoriService->createCategory($request->validated());

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = $this->kategoriService->getCategoryById($id);
        return view('admin.kategori.edit-kategori', compact('category'));
    }

    public function update(UpdateKategoriRequest $request, $id)
    {
        $this->kategoriService->updateCategory($id, $request->validated());

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->kategoriService->deleteCategory($id);

        return redirect()->route('admin.kategori')->with('success', 'Kategori berhasil dihapus!');
    }
}
