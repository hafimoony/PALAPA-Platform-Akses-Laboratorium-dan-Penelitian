<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositRefund extends Model
{
    protected $table = 'deposit_refunds';
    protected $primaryKey = 'refund_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'refund_id', 'invoice_id', 'ticket_id', 'original_deposit_amount',
        'deduction_amount', 'final_refund_amount', 'refund_vendor_id',
        'refund_account_number', 'refund_status', 'processed_at'
    ];
}
