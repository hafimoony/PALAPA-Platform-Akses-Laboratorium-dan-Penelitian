<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseTransaction extends Model
{
    protected $table = 'base_transactions';
    protected $primaryKey = 'transaction_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'transaction_number',
        'user_id',
        'total_amount',
        'created_at',
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class, 'booking_id', 'transaction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
