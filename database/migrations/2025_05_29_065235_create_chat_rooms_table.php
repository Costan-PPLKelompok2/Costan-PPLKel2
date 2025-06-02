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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kos_id')->constrained('kos')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade'); // Penyewa
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');  // Pemilik Kos
            $table->timestamps(); // Untuk last_activity atau created_at

            // Opsional: untuk mencegah duplikasi chat room untuk kombinasi yang sama
            $table->unique(['kos_id', 'tenant_id', 'owner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
