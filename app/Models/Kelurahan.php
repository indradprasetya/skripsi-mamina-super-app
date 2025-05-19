<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'd_kelurahan';
    protected $primaryKey = 'id_kelurahan';
    public $timestamps = false;

    protected $fillable = ['nama_kelurahan', 'id_kecamatan'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan');
    }
}
