<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('user');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $pengeluarans = $query->latest('tanggal')->paginate(12);
        
        $totalBulanIni = Pengeluaran::whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->sum('jumlah');

        $view = (auth()->user()->role === 'kasir') ? 'kasir.pengeluaran.index' : 'admin.pengeluaran.index';
        return view($view, compact('pengeluarans', 'totalBulanIni'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'kategori' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Pengeluaran::create([
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

}
