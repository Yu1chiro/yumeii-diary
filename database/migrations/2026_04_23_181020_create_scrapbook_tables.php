<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alter users table for admin
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(true);
        });

        // Stories Table
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Story Images Table (For Carousel)
        Schema::create('story_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('story_id')->constrained()->cascadeOnDelete();
            $table->string('image_url');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Memories Table
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->date('memory_date');
            $table->timestamps();
        });

        // Timeline Events Table
        Schema::create('timeline_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->date('event_date');
            $table->timestamps();
        });

        // Letters Table
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_private')->default(false);
            $table->timestamps();
        });

        // Galleries Table (Dengan styling enterprise & aesthetic)
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_url');
            $table->enum('layout_style', ['standard', 'grid-2x2', 'grid-3x3', 'polaroid', 'photobooth'])->default('polaroid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('letters');
        Schema::dropIfExists('timeline_events');
        Schema::dropIfExists('memories');
        Schema::dropIfExists('story_images');
        Schema::dropIfExists('stories');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};