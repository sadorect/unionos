<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('weekly_email_enabled')->default(true);
            $table->unsignedTinyInteger('weekly_day_of_week')->default(1);
            $table->unsignedTinyInteger('weekly_hour')->default(18);
            $table->boolean('monthly_email_enabled')->default(true);
            $table->unsignedTinyInteger('monthly_day_of_month')->default(1);
            $table->unsignedTinyInteger('monthly_hour')->default(18);
            $table->boolean('conversation_email_enabled')->default(false);
            $table->boolean('conversation_in_app_enabled')->default(true);
            $table->unsignedTinyInteger('conversation_hour')->default(18);
            $table->string('timezone')->default('UTC');
            $table->boolean('quiet_mode_enabled')->default(false);
            $table->timestamps();

            $table->unique('user_id');
        });

        Schema::create('notification_dispatch_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kind');
            $table->string('channel');
            $table->string('status')->default('sent');
            $table->string('reason')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'kind', 'channel']);
            $table->index(['status', 'delivered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_dispatch_logs');
        Schema::dropIfExists('notification_preferences');
    }
};
