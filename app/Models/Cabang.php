<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'm_cabang';
    protected $primaryKey = 'id_cabang';
    public $timestamps = false;

    protected $fillable = ['nama_cabang'];
}
