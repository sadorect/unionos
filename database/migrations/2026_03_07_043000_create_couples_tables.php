<?php

use App\Domains\Couple\Enums\CoupleStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default(CoupleStatus::Pending->value);
            $table->timestamps();
        });

        Schema::create('couple_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('slot');
            $table->timestamp('joined_at');
            $table->boolean('is_active')->default(true);
            $table->unique(['couple_id', 'user_id']);
            $table->unique(['couple_id', 'slot']);
        });

        Schema::create('couple_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couple_invites');
        Schema::dropIfExists('couple_memberships');
        Schema::dropIfExists('couples');
    }
};
