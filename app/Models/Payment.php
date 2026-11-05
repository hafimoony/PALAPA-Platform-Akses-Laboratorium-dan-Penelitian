
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'payment_id';

    public $incrementing = false;

    protected $keyType = 'string';

    // Tabel payments tidak memiliki updated_at
    public $timestamps = false;

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'vendor_id',
        'gateway_transaction_id',
        'paid_amount',
        'payment_proof_url',
        'paid_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id',
            'invoice_id'
        );
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(
            PaymentVendor::class,
            'vendor_id',
            'vendor_id'
        );
    }
}

