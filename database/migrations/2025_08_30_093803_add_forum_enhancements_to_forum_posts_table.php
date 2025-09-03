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
        Schema::table('forum_posts', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('forum_posts', 'category')) {
                $table->string('category')->default('umum')->after('title');
            }
            if (!Schema::hasColumn('forum_posts', 'views_count')) {
                $table->integer('views_count')->default(0)->after('content');
            }
            if (!Schema::hasColumn('forum_posts', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('views_count');
            }
            if (!Schema::hasColumn('forum_posts', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('is_pinned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            // Check if columns exist before dropping them
            if (Schema::hasColumn('forum_posts', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('forum_posts', 'views_count')) {
                $table->dropColumn('views_count');
            }
            if (Schema::hasColumn('forum_posts', 'is_pinned')) {
                $table->dropColumn('is_pinned');
            }
            if (Schema::hasColumn('forum_posts', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
        });
    }
};
