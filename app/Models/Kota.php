<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'd_kota';
    protected $primaryKey = 'id_kota';
    public $timestamps = false;

    protected $fillable = ['nama_kota', 'id_provinsi'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi');
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'id_kota');
    }
}
