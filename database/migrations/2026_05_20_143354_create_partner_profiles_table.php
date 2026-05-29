<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama (Yuii / Meii)
            $table->string('icon_url')->nullable(); // Thumbnail Icon
            $table->json('favorite_music')->nullable(); // Tags Musik
            $table->json('traits')->nullable(); // Tags Karakteristik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_profiles');
    }
};