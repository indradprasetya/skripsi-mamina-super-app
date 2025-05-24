<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_pelanggan';
    protected $primaryKey = 'id_pelanggan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nomor',
        'telp',
        'email',
        'nama',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'id_provinsi',
        'id_kota',
        'id_kecamatan',
        'id_kelurahan',
        'id_cabang',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'mobile_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    // remember me
    public function getRememberTokenName()
    {
        return 'mobile_token';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $today = now()->format('dmy');
            $countToday = static::whereDate('created_at', now()->toDateString())->count() + 1;
            $user->nomor = $today . str_pad($countToday, 2, '0', STR_PAD_LEFT);

            //daftar
            $user->daftar = 'Sendiri';
        });
    }
}
