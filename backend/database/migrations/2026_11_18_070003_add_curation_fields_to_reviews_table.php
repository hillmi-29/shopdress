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
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('comment');
            $table->boolean('is_featured')->default(false)->after('is_visible');
            $table->unsignedInteger('sort_order')->default(0)->after('is_featured');

            $table->index(['is_visible', 'is_featured'], 'reviews_visibility_featured_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_visibility_featured_index');
            $table->dropColumn(['is_visible', 'is_featured', 'sort_order']);
        });
    }
};
