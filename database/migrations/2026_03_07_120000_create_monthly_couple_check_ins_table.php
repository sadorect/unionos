<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_couple_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('month', 7);
            $table->unsignedTinyInteger('felt_heard');
            $table->unsignedTinyInteger('felt_appreciated');
            $table->unsignedTinyInteger('conflict_safety');
            $table->unsignedTinyInteger('emotional_closeness');
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['user_id', 'month']);
            $table->index(['couple_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_couple_check_ins');
    }
};
