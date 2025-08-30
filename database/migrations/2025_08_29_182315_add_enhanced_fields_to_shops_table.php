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
        Schema::table('shops', function (Blueprint $table) {
            // Add new fields that don't exist yet
            $table->string('whatsapp', 20)->nullable()->after('phone');
            $table->string('website')->nullable()->after('whatsapp');
            $table->string('image_path')->nullable()->after('opening_hours');
            $table->json('gallery')->nullable()->after('image_path'); // For multiple images in future
            
            // Modify existing fields to better types if needed
            $table->text('opening_hours')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp',
                'website', 
                'image_path',
                'gallery'
            ]);
            
            // Revert opening_hours back to string
            $table->string('opening_hours')->nullable()->change();
        });
    }
};
