<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lab_entry_passes')) {
            Schema::create('lab_entry_passes', function (Blueprint $table) {
                $table->string('pass_id', 50)->primary();
                $table->string('booking_id', 50);
                $table->string('qr_code_data', 120)->unique();
                $table->string('checkin_status', 30)->default('pending');
                $table->timestamp('checked_in_at')->nullable();
                $table->string('checked_in_by_staff_id', 50)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('incident_tickets')) {
            Schema::create('incident_tickets', function (Blueprint $table) {
                $table->string('ticket_id', 50)->primary();
                $table->string('booking_id', 50);
                $table->string('equipment_id', 50)->nullable();
                $table->string('reported_by_staff_id', 50);
                $table->string('severity', 30)->default('minor');
                $table->text('description');
                $table->decimal('repair_cost_estimate', 12, 2)->default(0);
                $table->string('status', 30)->default('open');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('deposit_refunds')) {
            Schema::create('deposit_refunds', function (Blueprint $table) {
                $table->string('refund_id', 50)->primary();
                $table->string('booking_id', 50);
                $table->string('processed_by_staff_id', 50);
                $table->decimal('original_deposit', 12, 2)->default(0);
                $table->decimal('deduction_amount', 12, 2)->default(0);
                $table->decimal('refunded_amount', 12, 2)->default(0);
                $table->string('refund_status', 30)->default('refunded');
                $table->text('notes')->nullable();
                $table->timestamp('refunded_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_refunds');
        Schema::dropIfExists('incident_tickets');
        Schema::dropIfExists('lab_entry_passes');
    }
};
