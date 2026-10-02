<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

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
        'issued_at',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
