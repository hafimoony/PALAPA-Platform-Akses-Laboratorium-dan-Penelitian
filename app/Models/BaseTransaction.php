
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BaseTransaction extends Model
{
    protected $table = 'base_transactions';

    protected $primaryKey = 'transaction_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'transaction_id',
        'transaction_number',
        'user_id',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    public function booking(): HasOne
    {
        return $this->hasOne(
            Booking::class,
            'booking_id',
            'transaction_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'user_id'
        );
    }
}
