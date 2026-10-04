<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nonaktifkan pengecekan Foreign Key agar tidak terhalang urutan pembuatan tabel
        Schema::disableForeignKeyConstraints();

        // 1. Laboratories
        Schema::create('laboratories', function (Blueprint $table) {
            $table->string('lab_id', 50)->primary();
            $table->string('campus_name', 100);
            $table->string('faculty', 100);
            $table->string('lab_name', 100);
            $table->string('category', 30);
            $table->integer('capacity');
            $table->text('address_description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('base_price_per_session', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Lab Equipments
        Schema::create('lab_equipments', function (Blueprint $table) {
            $table->string('equipment_id', 50)->primary();
            $table->string('lab_id', 50);
            $table->string('equipment_name', 100);
            $table->string('brand_model', 100)->nullable();
            $table->string('serial_number', 100)->unique()->nullable();
            $table->integer('quantity_total');
            $table->decimal('rental_price_per_unit', 12, 2)->default(0.00);
            $table->string('status', 30)->default('available');
            $table->timestamps();

            $table->foreign('lab_id')->references('lab_id')->on('laboratories')->onDelete('cascade');
        });

        // 3. Base Transactions
        Schema::create('base_transactions', function (Blueprint $table) {
            $table->string('transaction_id', 50)->primary();
            $table->string('transaction_number', 50)->unique();
            $table->string('user_id', 50);
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users');
        });

        // 4. Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('booking_id', 50)->primary();
            $table->string('booking_code', 20)->unique();
            $table->string('lab_id', 50);
            $table->date('reservation_date');
            $table->string('session_time', 50);
            $table->integer('total_participants');
            $table->text('proposal_file_url')->nullable();
            $table->boolean('need_technician_assistant')->default(false);
            $table->string('status', 30)->default('pending_review');
            $table->text('rejection_reason')->nullable();
            $table->string('approved_by_staff_id', 50)->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('transaction_id')->on('base_transactions')->onDelete('cascade');
            $table->foreign('lab_id')->references('lab_id')->on('laboratories');
            $table->foreign('approved_by_staff_id')->references('user_id')->on('users');
        });

        // 5. Booking Equipment Items
        Schema::create('booking_equipment_items', function (Blueprint $table) {
            $table->string('item_id', 50)->primary();
            $table->string('booking_id', 50);
            $table->string('equipment_id', 50);
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal_price', 12, 2);
            $table->timestamps();

            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->foreign('equipment_id')->references('equipment_id')->on('lab_equipments');
        });

        // 6. Invoices
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
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
        });

        // Aktifkan kembali pengecekan Foreign Key
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('booking_equipment_items');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('base_transactions');
        Schema::dropIfExists('lab_equipments');
        Schema::dropIfExists('laboratories');
        Schema::enableForeignKeyConstraints();
    }
};
