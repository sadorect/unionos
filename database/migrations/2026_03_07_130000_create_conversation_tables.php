<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('started_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('current_turn_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pack_type');
            $table->unsignedInteger('pack_version')->default(1);
            $table->string('status')->default('active');
            $table->timestamp('ground_rules_confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });

        Schema::create('conversation_turns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('conversation_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('prompt_key');
            $table->text('response_text');
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_turns');
        Schema::dropIfExists('conversation_sessions');
    }
};
