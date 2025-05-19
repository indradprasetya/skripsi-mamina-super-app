<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    protected $table = 'm_anak';
    protected $primaryKey = 'id_anak';

    protected $fillable = [
     'id_pelanggan',
     'nama',
     'tempat',
     'jenis_kelamin',
     'tgl_lahir',
    ];

    public $timestamps = false;

    public function records() {
        return $this->hasMany(Record::class, 'id_anak');
    }
}
