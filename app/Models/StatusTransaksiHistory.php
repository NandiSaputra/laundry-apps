<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusTransaksiHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'status_lama',
        'status_baru',
        'user_id',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
