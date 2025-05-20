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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable(); 
            $table->text('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            
            // Menambahkan price sesuai dengan model
            $table->decimal('price', 12, 2)->nullable(); // Menambahkan field price
            
            // Preferensi pencarian sesuai model
            $table->string('preferred_location')->nullable();
            $table->string('preferred_kos_type')->nullable();
            $table->json('preferred_facilities')->nullable();  
            $table->timestamps();
            $table->softDeletes();

        Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['pemilik', 'penyewa'])->default('penyewa')->after('email');
        });
    }

   



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
