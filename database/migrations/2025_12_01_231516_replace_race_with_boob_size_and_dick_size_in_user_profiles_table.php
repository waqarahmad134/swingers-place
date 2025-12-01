<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('race');
            $table->string('boob_size')->nullable()->after('piercings');
            $table->string('dick_size')->nullable()->after('boob_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('race')->nullable();
            $table->dropColumn(['boob_size', 'dick_size']);
        });
    }
};
