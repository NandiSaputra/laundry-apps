<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $layanans = \App\Models\Layanan::active()->with('kategori')->get();
        $settings = app(\App\Services\SettingService::class)->getAll();
        return view('welcome', compact('layanans', 'settings'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'kode' => 'required|string',
        ]);

        $transaksi = Transaksi::with(['pelanggan', 'details.layanan', 'histories.user'])
            ->where('kode_transaksi', $request->kode)
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Kode transaksi tidak ditemukan. Mohon periksa kembali kode Anda.');
        }

        return view('public.track', compact('transaksi'));
    }
}
