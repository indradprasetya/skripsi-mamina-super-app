<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'm_tumbuhkembang';
    protected $primaryKey = 'id_tumbuhkembang';

    protected $fillable = [
        'id_anak',
        'tanggal_catatan',
        'umur',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class, 'id_anak');
    }
}
