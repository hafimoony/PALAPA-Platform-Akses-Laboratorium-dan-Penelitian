<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_vendors')) {
            Schema::create('payment_vendors', function (Blueprint $table) {
                $table->string('vendor_id', 50)->primary();
                $table->string('vendor_code', 50)->unique();
                $table->string('vendor_name', 100);
                $table->string('vendor_type', 50)->default('virtual_account');
                $table->string('account_number_or_merchant_id', 100)->nullable();
                $table->decimal('admin_fee', 12, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_vendors');
    }
};
