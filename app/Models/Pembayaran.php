<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'user_id',
        'kode_pembayaran',
        'jumlah',
        'metode',
        'referensi',
        'tanggal_bayar',
        'catatan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class)->withTrashed();
    }
}
