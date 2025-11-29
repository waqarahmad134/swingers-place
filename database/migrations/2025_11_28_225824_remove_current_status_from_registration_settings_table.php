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
        Schema::table('registration_settings', function (Blueprint $table) {
            if (Schema::hasColumn('registration_settings', 'current_status')) {
                $table->dropColumn('current_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_settings', function (Blueprint $table) {
            $table->enum('current_status', ['open', 'closed'])->default('open')->after('admin_approval');
        });
    }
};
