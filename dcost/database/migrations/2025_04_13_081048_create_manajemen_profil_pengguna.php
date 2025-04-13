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
        Schema::create('manajemen_profil_pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nomor_telepon')->nullable();
            $table->text('preferensi_pencarian')->nullable();
            $table->string('foto_profil')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes(); // untuk fitur hapus akun (soft delete)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manajemen_profil_pengguna');
    }
};
