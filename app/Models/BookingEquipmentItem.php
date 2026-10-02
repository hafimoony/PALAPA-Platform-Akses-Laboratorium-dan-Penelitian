<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingEquipmentItem extends Model
{
    protected $table = 'booking_equipment_items';
    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'booking_id',
        'equipment_id',
        'quantity',
        'unit_price',
        'subtotal_price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function equipment()
    {
        return $this->belongsTo(LabEquipment::class, 'equipment_id', 'equipment_id');
    }
}
