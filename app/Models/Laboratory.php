<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $table = 'laboratories';
    protected $primaryKey = 'lab_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'lab_id', 'campus_name', 'faculty', 'lab_name', 'category',
        'capacity', 'address_description', 'latitude', 'longitude',
        'base_price_per_session', 'is_active'
    ];

    public function equipments() {
        return $this->hasMany(LabEquipment::class, 'lab_id', 'lab_id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'lab_id', 'lab_id');
    }
}
