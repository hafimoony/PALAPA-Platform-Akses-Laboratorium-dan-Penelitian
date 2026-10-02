<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabEntryPass extends Model
{
    protected $table = 'lab_entry_passes';
    protected $primaryKey = 'pass_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'pass_id',
        'booking_id',
        'qr_code_data',
        'checkin_status',
        'checked_in_at',
        'verified_by_staff_id'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }
}
