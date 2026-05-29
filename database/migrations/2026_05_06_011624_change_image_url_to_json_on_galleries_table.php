<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('image_url');
        });

        Schema::table('galleries', function (Blueprint $table) {
            // Buat ulang dengan tipe JSON untuk multiple images
            $table->json('images_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('images_url');
            $table->string('image_url')->nullable();
        });
    }
};