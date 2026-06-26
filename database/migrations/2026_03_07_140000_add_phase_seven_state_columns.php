<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('monthly_couple_check_ins', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('monthly_couple_check_ins', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
