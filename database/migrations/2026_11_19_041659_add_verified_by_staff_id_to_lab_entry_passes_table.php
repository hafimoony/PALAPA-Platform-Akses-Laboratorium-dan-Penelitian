<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_entry_passes', function (Blueprint $table) {
            if (!Schema::hasColumn('lab_entry_passes', 'verified_by_staff_id')) {
                $table->string('verified_by_staff_id', 50)->nullable()->after('checked_in_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lab_entry_passes', function (Blueprint $table) {
            $table->dropColumn('verified_by_staff_id');
        });
    }
};
