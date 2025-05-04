<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kos_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kos_id');
            $table->ipAddress('ip')->nullable();
            $table->timestamps();

            $table->foreign('kos_id')
                  ->references('id')
                  ->on('kos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kos_views');
    }
};