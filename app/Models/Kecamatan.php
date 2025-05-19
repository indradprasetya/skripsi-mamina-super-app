<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'd_kecamatan';
    protected $primaryKey = 'id_kecamatan';
    public $timestamps = false;

    protected $fillable = ['nama_kecamatan', 'id_kota'];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota');
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan');
    }
}
