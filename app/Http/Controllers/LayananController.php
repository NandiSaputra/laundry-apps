<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Layanan\StoreLayananRequest;
use App\Http\Requests\Admin\Layanan\UpdateLayananRequest;
use App\Services\LayananService;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    protected $layananService;
    protected $kategoriService;

    public function __construct(LayananService $layananService, KategoriService $kategoriService)
    {
        $this->layananService = $layananService;
        $this->kategoriService = $kategoriService;
    }

    public function index()
    {
        $layanans = $this->layananService->getAllLayanans();
        $view = (auth()->user()->role === 'kasir') ? 'kasir.layanan.index' : 'admin.layanan.index';
        return view($view, compact('layanans'));
    }

    public function create()
    {
        $categories = $this->kategoriService->getAllCategories();
        return view('admin.layanan.create-layanan', compact('categories'));
    }

    public function store(StoreLayananRequest $request)
    {
        $this->layananService->createLayanan($request->validated());

        return redirect()->route(auth()->user()->role . '.layanan')->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $layanan = $this->layananService->getLayananById($id);
        $categories = $this->kategoriService->getAllCategories();
        return view('admin.layanan.edit-layanan', compact('layanan', 'categories'));
    }

    public function update(UpdateLayananRequest $request, $id)
    {
        $this->layananService->updateLayanan($id, $request->validated());

        return redirect()->route(auth()->user()->role . '.layanan')->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->layananService->deleteLayanan($id);

        return redirect()->route(auth()->user()->role . '.layanan')->with('success', 'Layanan berhasil dihapus!');
    }
}
