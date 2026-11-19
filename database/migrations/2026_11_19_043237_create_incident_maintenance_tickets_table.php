<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('incident_maintenance_tickets')) {
            Schema::create('incident_maintenance_tickets', function (Blueprint $table) {
                $table->string('ticket_id', 50)->primary();
                $table->string('booking_id', 50);
                $table->string('equipment_id', 50)->nullable();
                $table->string('reported_by_staff_id', 50)->nullable();
                $table->string('severity', 30)->default('minor');
                $table->text('description');
                $table->decimal('repair_cost_estimate', 12, 2)->default(0);
                $table->decimal('penalty_deducted_from_deposit', 12, 2)->default(0);
                $table->string('status', 30)->default('open');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_maintenance_tickets');
    }
};
