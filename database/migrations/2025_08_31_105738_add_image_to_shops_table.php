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
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('shops', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            // gallery already exists from previous migration, skip it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
