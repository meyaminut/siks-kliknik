<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'no_hp',
        'jenis_kelamin',
        'role',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mengatur casting tipe data
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'user_id');
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'pasien_id');
    }
}
