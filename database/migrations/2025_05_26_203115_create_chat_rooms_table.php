<?php
// File: database/migrations/XXXX_XX_XX_XXXXXX_create_chat_rooms_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kos_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('kos_id')->references('id')->on('kos')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('users_profile')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users_profile')->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi chat room
            $table->unique(['kos_id', 'tenant_id'], 'unique_kos_tenant_chat');

            // Indexes untuk performa
            $table->index(['tenant_id', 'updated_at']);
            $table->index(['owner_id', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_rooms');
    }
};

?>