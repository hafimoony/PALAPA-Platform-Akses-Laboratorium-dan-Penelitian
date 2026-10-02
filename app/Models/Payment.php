<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Gunakan false jika tabel payments tidak memiliki kolom updated_at

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'vendor_id',
        'gateway_transaction_id',
        'paid_amount',
        'payment_proof_url',
        'paid_at',
        'created_at'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function vendor()
    {
        return $this->belongsTo(PaymentVendor::class, 'vendor_id', 'vendor_id');
    }
}
