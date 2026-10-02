<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'booking_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'booking_id', 'booking_code', 'lab_id', 'reservation_date',
        'session_time', 'total_participants', 'proposal_file_url',
        'need_technician_assistant', 'status', 'rejection_reason', 'approved_by_staff_id'
    ];

    public function baseTransaction() {
        return $this->belongsTo(BaseTransaction::class, 'booking_id', 'transaction_id');
    }

    public function laboratory() {
        return $this->belongsTo(Laboratory::class, 'lab_id', 'lab_id');
    }

    public function equipmentItems() {
        return $this->hasMany(BookingEquipmentItem::class, 'booking_id', 'booking_id');
    }

    public function invoice() {
        return $this->hasOne(Invoice::class, 'booking_id', 'booking_id');
    }

    public function entryPass() {
        return $this->hasOne(LabEntryPass::class, 'booking_id', 'booking_id');
    }
}
