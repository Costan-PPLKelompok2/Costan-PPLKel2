<?php
// File: database/migrations/XXXX_XX_XX_XXXXXX_create_messages_table.php

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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('message');
            $table->enum('message_type', ['text', 'image', 'file'])->default('text');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users_profile')->onDelete('cascade');

            // Indexes untuk performa query
            $table->index(['chat_room_id', 'created_at']);
            $table->index(['sender_id', 'is_read']);
            $table->index(['is_read', 'chat_room_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};

?>

