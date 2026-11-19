<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabEquipment extends Model
{
    protected $table = 'lab_equipments';
    protected $primaryKey = 'equipment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'equipment_id', 'lab_id', 'equipment_name', 'brand_model',
        'serial_number', 'quantity_total', 'rental_price_per_unit', 'status'
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class, 'lab_id', 'lab_id');
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingEquipmentItem::class, 'equipment_id', 'equipment_id');
    }
}
