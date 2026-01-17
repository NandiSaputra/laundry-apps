<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_transaksi',
        'qr_code',
        'pelanggan_id',
        'user_id',
        'tanggal_masuk',
        'tanggal_estimasi',
        'tanggal_selesai',
        'tanggal_diambil',
        'status',
        'subtotal',
        'diskon',
        'total',
        'total_dibayar',
        'status_pembayaran',
        'catatan',
        'catatan_khusus',
        'parfum',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_estimasi' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_diambil' => 'datetime',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2',
        'total_dibayar' => 'decimal:2',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function histories()
    {
        return $this->hasMany(StatusTransaksiHistory::class)->orderBy('created_at', 'asc');
    }
}
