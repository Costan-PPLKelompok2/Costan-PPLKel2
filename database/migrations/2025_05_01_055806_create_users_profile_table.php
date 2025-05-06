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
        Schema::create('users_profile', function (Blueprint $table) {
            $table->id();
            // Menambahkan kolom-kolom untuk profil pengguna
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('price_range', ['500 Ribu - 1 Juta /bulan', '1 Juta - 2 Juta /bulan', '2 Juta - 3 Juta /bulan'])->nullable();
            $table->string('location')->nullable();
            $table->enum('room_type', ['Kosan Pria dan Wanita', 'Kosan Khusus Pria', 'Kosan Khusus Wanita'])->nullable();
            $table->text('facilities')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_profile');
    }
};
