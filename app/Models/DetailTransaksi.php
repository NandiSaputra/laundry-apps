<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailTransaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'layanan_id',
        'nama_layanan',
        'harga_satuan',
        'jumlah',
        'satuan',
        'subtotal',
        'catatan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'jumlah' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class)->withTrashed();
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class)->withTrashed();
    }
}
