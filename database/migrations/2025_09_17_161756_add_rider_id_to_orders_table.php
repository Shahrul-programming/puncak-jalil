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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('rider_id')->nullable()->constrained()->onDelete('set null')->after('shop_id');
            $table->timestamp('rider_assigned_at')->nullable()->after('rider_id');
            $table->timestamp('rider_picked_up_at')->nullable()->after('rider_assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['rider_id']);
            $table->dropColumn(['rider_id', 'rider_assigned_at', 'rider_picked_up_at']);
        });
    }
};
