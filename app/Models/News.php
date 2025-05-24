<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'id_news';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'title',
        'image',
        'thumb',
        'content',
        'created_at',
        'created_by',
    'updated_at',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
