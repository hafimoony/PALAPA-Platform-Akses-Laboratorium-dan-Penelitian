<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    // Set Custom Primary Key
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password_hash', // Sesuai kolom di skema SQL
        'phone_number',
        'role',
    ];

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Memberitahu Laravel bahwa kolom password bernama 'password_hash'
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    public function externalProfile()
{
    return $this->hasOne(ExternalProfile::class, 'user_id', 'user_id');
}
}
