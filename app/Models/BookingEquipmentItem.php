
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingEquipmentItem extends Model
{
    protected $table = 'booking_equipment_items';

    protected $primaryKey = 'item_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'item_id',
        'booking_id',
        'equipment_id',
        'quantity',
        'unit_price',
        'subtotal_price',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal_price' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(
            Booking::class,
            'booking_id',
            'booking_id'
        );
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(
            LabEquipment::class,
            'equipment_id',
            'equipment_id'
        );
    }
}
