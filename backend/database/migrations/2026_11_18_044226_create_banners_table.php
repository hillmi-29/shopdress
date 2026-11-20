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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('position')->default('hero')->index();
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->string('highlight')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('caption')->nullable();
            $table->string('caption_sub')->nullable();
            $table->string('badge')->nullable();
            $table->string('badge_style')->default('light');
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('secondary_label')->nullable();
            $table->string('secondary_url')->nullable();
            $table->text('footnote')->nullable();
            $table->string('coupon_code')->nullable();
            $table->string('rating_text')->nullable();
            $table->boolean('show_rating')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
