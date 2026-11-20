<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVendor extends Model
{
    protected $table = 'payment_vendors';
    protected $primaryKey = 'vendor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];
}
