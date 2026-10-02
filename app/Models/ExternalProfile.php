<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalProfile extends Model
{
    use HasFactory;

    protected $table = 'external_profiles';
    protected $primaryKey = 'profile_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Tabel external_profiles hanya memiliki created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        'profile_id',
        'user_id',
        'institution_name',
        'institution_type',
        'address',
        'tax_number_npwp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
