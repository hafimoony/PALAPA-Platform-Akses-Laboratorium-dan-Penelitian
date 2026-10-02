<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_profiles', function (Blueprint $table) {
            $table->string('profile_id', 50)->primary();
            $table->string('user_id', 50)->unique();
            $table->string('institution_name', 150);
            $table->string('institution_type', 30);
            $table->text('address')->nullable();
            $table->string('tax_number_npwp', 30)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_profiles');
    }
};
