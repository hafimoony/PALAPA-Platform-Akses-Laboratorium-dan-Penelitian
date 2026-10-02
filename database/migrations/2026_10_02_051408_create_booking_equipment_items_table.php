<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_equipment_items', function (Blueprint $table) {
            $table->string('item_id', 50)->primary();
            $table->string('booking_id', 50);
            $table->string('equipment_id', 50);
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal_price', 12, 2);

            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->foreign('equipment_id')->references('equipment_id')->on('lab_equipments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_equipment_items');
    }
};
