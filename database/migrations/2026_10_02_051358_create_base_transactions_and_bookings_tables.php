<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('base_transactions', function (Blueprint $table) {
            $table->string('transaction_id', 50)->primary();
            $table->string('transaction_number', 50)->unique();
            $table->string('user_id', 50);
            $table->decimal('total_amount', 12, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('user_id')->on('users');
        });

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

            $table->foreign('booking_id')->references('transaction_id')->on('base_transactions')->onDelete('cascade');
            $table->foreign('lab_id')->references('lab_id')->on('laboratories');
            $table->foreign('approved_by_staff_id')->references('user_id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('base_transactions');
    }
};
