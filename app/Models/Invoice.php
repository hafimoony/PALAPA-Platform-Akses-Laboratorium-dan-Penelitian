
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $primaryKey = 'invoice_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'booking_id',
        'lab_rental_fee',
        'equipment_fee',
        'technician_fee',
        'security_deposit_fee',
        'tax_fee',
        'vendor_admin_fee',
        'grand_total',
        'payment_status',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'lab_rental_fee' => 'decimal:2',
            'equipment_fee' => 'decimal:2',
            'technician_fee' => 'decimal:2',
            'security_deposit_fee' => 'decimal:2',
            'tax_fee' => 'decimal:2',
            'vendor_admin_fee' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'expired_at' => 'datetime',
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
}
