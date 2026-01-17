<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kategori_layanans';

    protected $fillable = [
       'nama',
         'deskripsi',
         'is_active',
    ];

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the services for the category.
     */
    public function layanans()
    {
        return $this->hasMany(Layanan::class, 'kategori_id');
    }
}
