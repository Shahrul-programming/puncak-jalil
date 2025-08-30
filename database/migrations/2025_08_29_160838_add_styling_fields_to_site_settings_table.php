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
        Schema::table('site_settings', function (Blueprint $table) {
            // Headline styling
            $table->string('headline_font_size')->default('text-4xl')->after('headline');
            $table->string('headline_color')->default('text-gray-900')->after('headline_font_size');
            $table->string('headline_alignment')->default('text-center')->after('headline_color');
            $table->text('description')->nullable()->after('headline_alignment');
            $table->string('background_color')->nullable()->after('description');
            
            // Contact info
            $table->string('contact_phone')->nullable()->after('background_color');
            $table->string('contact_email')->nullable()->after('contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'headline_font_size',
                'headline_color', 
                'headline_alignment',
                'description',
                'background_color',
                'contact_phone',
                'contact_email',
            ]);
        });
    }
};
