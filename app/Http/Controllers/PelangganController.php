<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Pelanggan\StorePelangganRequest;
use App\Http\Requests\Admin\Pelanggan\UpdatePelangganRequest;
use App\Models\Pelanggan;
use App\Services\PelangganService;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    protected $pelangganService;

    public function __construct(PelangganService $pelangganService)
    {
        $this->pelangganService = $pelangganService;
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $keyword = $request->get('q');
            $query = Pelanggan::query();
            
            if ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('kode_pelanggan', 'like', "%{$keyword}%")
                      ->orWhere('telepon', 'like', "%{$keyword}%")
                      ->orWhere('alamat', 'like', "%{$keyword}%");
                });
            }
            
            return response()->json($query->latest()->limit(20)->get());
        }

        $pelanggans = $this->pelangganService->getAllPelanggans();
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('kasir.pelanggan');
        }
        return view('admin.pelanggan.create-pelanggan');
    }

    public function store(StorePelangganRequest $request)
    {
        $this->pelangganService->createPelanggan($request->validated());
        return redirect()->route(auth()->user()->role . '.pelanggan')->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (auth()->user()->role === 'kasir') {
            return redirect()->route('kasir.pelanggan');
        }
        $pelanggan = $this->pelangganService->getPelangganById($id);
        return view('admin.pelanggan.edit-pelanggan', compact('pelanggan'));
    }

    public function update(UpdatePelangganRequest $request, $id)
    {
        $this->pelangganService->updatePelanggan($id, $request->validated());
        return redirect()->route(auth()->user()->role . '.pelanggan')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->pelangganService->deletePelanggan($id);
        return redirect()->route(auth()->user()->role . '.pelanggan')->with('success', 'Pelanggan berhasil dihapus!');
    }
}
