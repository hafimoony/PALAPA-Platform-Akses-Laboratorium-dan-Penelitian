<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepositRefund extends Model
{
    protected $table = 'deposit_refunds';
    protected $primaryKey = 'refund_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'refund_id',
        'booking_id',
        'processed_by_staff_id',
        'original_deposit',
        'deduction_amount',
        'refunded_amount',
        'refund_status',
        'notes',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'original_deposit' => 'decimal:2',
            'deduction_amount' => 'decimal:2',
            'refunded_amount'  => 'decimal:2',
            'refunded_at'      => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
