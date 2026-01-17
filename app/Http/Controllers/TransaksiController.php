<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Transaksi\StoreTransaksiRequest;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Services\TransaksiService;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    protected $transaksiService;

    public function __construct(TransaksiService $transaksiService)
    {
        $this->transaksiService = $transaksiService;
    }

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Transaksi::with(['pelanggan', 'user']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use($search) {
                $q->whereHas('pelanggan', function($sub) use($search) {
                    $sub->where('nama', 'like', "%$search%");
                })->orWhere('kode_transaksi', 'like', "%$search%");
            });
        }

        $transactions = $query->latest()->paginate(9);
        
        $userRole = auth()->user()->role;
        $view = 'admin.transaksi.index';
        if ($userRole === 'produksi') {
            $view = 'produksi.transaksi.index';
        }

        return view($view, compact('transactions'));
    }

    /**
     * Show the POS entry form.
     */
    public function create()
    {
        $layanans = Layanan::with('kategori')->active()->get();
        // $pelanggans = Pelanggan::latest()->get(); // Disable initial load for performance
        $pelanggans = [];
        
        $view = (auth()->user()->role === 'kasir') ? 'kasir.transaksi.pos' : 'admin.transaksi.pos';
        return view($view, compact('layanans', 'pelanggans'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(StoreTransaksiRequest $request)
    {
        try {
            $transaksi = $this->transaksiService->createTransaction($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat!',
                'redirect' => route(auth()->user()->role . '.transaksi.struk', $transaksi->id),
                'transaksi_id' => $transaksi->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the thermal receipt.
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'user', 'details'])->findOrFail($id);
        $view = (auth()->user()->role === 'kasir') ? 'kasir.transaksi.struk' : 'admin.transaksi.struk';
        return view($view, compact('transaksi'));
    }

    /**
     * Search transaction by code (for QR scanning).
     */
    public function searchByCode(Request $request)
    {
        $code = $request->get('code');
        $transaksi = Transaksi::where('kode_transaksi', $code)->first();

        if ($transaksi) {
            return response()->json([
                'success' => true,
                'id' => $transaksi->id,
                'status' => $transaksi->status,
                'status_pembayaran' => $transaksi->status_pembayaran,
                'pelanggan' => $transaksi->pelanggan->nama,
                'message' => 'Transaksi ditemukan!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode transaksi tidak valid.'
        ], 404);
    }

    /**
     * Update transaction status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,proses,cuci,setrika,packing,selesai,diambil,batal'
        ]);

        try {
            $transaksi = $this->transaksiService->updateStatus($id, $request->status);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status transaksi berhasil diperbarui!',
                    'status' => $transaksi->status
                ]);
            }

            return back()->with('success', 'Status transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Show mini label for thermal printing.
     */
    public function label($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'details.layanan'])->findOrFail($id);
        $view = (auth()->user()->role === 'kasir') ? 'kasir.transaksi.label' : 'admin.transaksi.label';
        return view($view, compact('transaksi'));
    }

    /**
     * Get transaction data in JSON format for modals.
     */
    public function getJson($id)
    {
        try {
            $transaksi = Transaksi::with(['pelanggan', 'details.layanan', 'histories.user'])->findOrFail($id);
            
            // Define next possible statuses for production workflow
            $nextStatuses = [
                'pending' => ['proses'],
                'proses' => ['cuci'],
                'cuci' => ['setrika'],
                'setrika' => ['packing'],
                'packing' => ['selesai'],
                'selesai' => [],
                'diambil' => [],
                'batal' => []
            ];

            return response()->json([
                'success' => true,
                'transaksi' => $transaksi,
                'next_available_statuses' => $nextStatuses[$transaksi->status] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
    }
}
