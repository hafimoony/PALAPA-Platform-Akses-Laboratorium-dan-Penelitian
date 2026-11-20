<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentMaintenanceTicket extends Model
{
    protected $table = 'incident_maintenance_tickets';
    protected $primaryKey = 'ticket_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $fillable = [
        'ticket_id',
        'booking_id',
        'equipment_id',
        'reported_by_staff_id',
        'severity',
        'description',
        'repair_cost_estimate',
        'penalty_deducted_from_deposit',
        'status',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(LabEquipment::class, 'equipment_id', 'equipment_id');
    }
}
