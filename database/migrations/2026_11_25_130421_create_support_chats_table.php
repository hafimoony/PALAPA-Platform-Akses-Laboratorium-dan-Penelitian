<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('support_chats')) {
            Schema::create('support_chats', function (Blueprint $table) {
                $table->id();
                $table->string('client_user_id', 50)->index();
                $table->string('sender_user_id', 50);
                $table->string('sender_role', 20); // 'client' atau 'staff'
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support_chats');
    }
};
