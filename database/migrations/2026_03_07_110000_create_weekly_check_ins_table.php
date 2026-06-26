<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->date('week_start_date');
            $table->unsignedTinyInteger('emotional_availability');
            $table->unsignedTinyInteger('communication_effort');
            $table->unsignedTinyInteger('stress_level');
            $table->text('appreciation_text')->nullable();
            $table->text('struggle_text')->nullable();
            $table->text('intention_text')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'week_start_date']);
            $table->index(['couple_id', 'week_start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_check_ins');
    }
};
