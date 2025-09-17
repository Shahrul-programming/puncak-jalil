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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('order_status_updates')->default(true);
            $table->boolean('order_confirmations')->default(true);
            $table->boolean('order_deliveries')->default(true);
            $table->boolean('promotional_emails')->default(false);
            $table->boolean('marketing_emails')->default(false);
            $table->enum('email_frequency', ['immediate', 'daily', 'weekly'])->default('immediate');
            $table->json('notification_channels')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
