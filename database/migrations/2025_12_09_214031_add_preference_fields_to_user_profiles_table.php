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
            $table->string('looks_important')->nullable()->after('smoking');
            $table->string('intelligence_important')->nullable()->after('looks_important');
            $table->string('relationship_orientation')->nullable()->after('relationship_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['looks_important', 'intelligence_important', 'relationship_orientation']);
        });
    }
};
