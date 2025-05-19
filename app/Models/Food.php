<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'makanan';
    protected $primaryKey = 'id_makanan';

    protected $fillable = [
        'id_kategori',
        'nama_makanan',
        'kalori',
        'protein',
        'karbohidrat',
        'lemak',
        'serat',
        'vitamin_a',
        'vitamin_c',
        'kalsium',
        'zat_besi',
        'satuan',
        'deskripsi',
    ];

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'id_kategori');
    }
}
