<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model
{
    use HasFactory;

    protected $table = 'laboratories';

    protected $primaryKey = 'lab_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'lab_id',
        'campus_id',
        'faculty',
        'lab_name',
        'category',
        'capacity',
        'address_description',
        'latitude',
        'longitude',
        'base_price_per_session',
        'is_active',
    ];

    // Relasi ke Campus
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'campus_id');
    }

    // Relasi ke Lab Equipment
    public function equipments(): HasMany
    {
        return $this->hasMany(LabEquipment::class, 'lab_id', 'lab_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'lab_id', 'lab_id');
    }
}
