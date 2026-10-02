<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('invoice_id', 50)->primary();
            $table->string('invoice_number', 50)->unique();
            $table->string('booking_id', 50)->unique();
            $table->decimal('lab_rental_fee', 12, 2);
            $table->decimal('equipment_fee', 12, 2)->default(0.00);
            $table->decimal('technician_fee', 12, 2)->default(0.00);
            $table->decimal('security_deposit_fee', 12, 2)->default(0.00);
            $table->decimal('tax_fee', 12, 2)->default(0.00);
            $table->decimal('vendor_admin_fee', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2);
            $table->string('payment_status', 30)->default('unpaid');
            $table->timestamp('expired_at');
            $table->timestamp('issued_at')->useCurrent();

            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
