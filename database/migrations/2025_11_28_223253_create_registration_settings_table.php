<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registration_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('open_registration')->default(true);
            $table->boolean('email_verification')->default(false);
            $table->boolean('admin_approval')->default(false);
            $table->json('regions')->nullable(); // Store regions as JSON: {north_america: true, europe: true, asia: true, other_regions: true}
            $table->timestamps();
        });

        // Insert default settings
        DB::table('registration_settings')->insert([
            'open_registration' => true,
            'email_verification' => false,
            'admin_approval' => false,
            'regions' => json_encode([
                'north_america' => true,
                'europe' => true,
                'asia' => true,
                'other_regions' => true,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_settings');
    }
};
