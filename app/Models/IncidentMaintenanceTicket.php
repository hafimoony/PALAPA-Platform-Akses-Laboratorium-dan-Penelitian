<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentMaintenanceTicket extends Model
{
    protected $table = 'incident_maintenance_tickets';
    protected $primaryKey = 'ticket_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ticket_id', 'booking_id', 'equipment_id', 'reported_by_staff_id',
        'damage_severity', 'chronology_description', 'evidence_photo_url',
        'repair_cost_estimate', 'deduct_from_deposit'
    ];
}
