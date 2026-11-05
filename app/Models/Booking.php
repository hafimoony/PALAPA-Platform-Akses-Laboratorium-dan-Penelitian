<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $primaryKey = 'booking_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'booking_id',
        'booking_code',
        'lab_id',
        'reservation_date',
        'session_time',
        'total_participants',
        'proposal_file_url',
        'need_technician_assistant',
        'status',
        'rejection_reason',
        'approved_by_staff_id',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'total_participants' => 'integer',
            'need_technician_assistant' => 'boolean',
        ];
    }

    public function baseTransaction(): BelongsTo
    {
        return $this->belongsTo(
            BaseTransaction::class,
            'booking_id',
            'transaction_id'
        );
    }

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(
            Laboratory::class,
            'lab_id',
            'lab_id'
        );
    }

    public function equipmentItems(): HasMany
    {
        return $this->hasMany(
            BookingEquipmentItem::class,
            'booking_id',
            'booking_id'
        );
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(
            Invoice::class,
            'booking_id',
            'booking_id'
        );
    }

    public function entryPass(): HasOne
    {
        return $this->hasOne(
            LabEntryPass::class,
            'booking_id',
            'booking_id'
        );
    }
}
