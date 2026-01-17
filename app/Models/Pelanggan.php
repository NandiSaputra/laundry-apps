<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_pelanggan',
        'nama',
        'telepon',
        'alamat',
        'email',
        'total_transaksi',
        'total_belanja',
    ];

    /**
     * Get the transactions for the customer.
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
