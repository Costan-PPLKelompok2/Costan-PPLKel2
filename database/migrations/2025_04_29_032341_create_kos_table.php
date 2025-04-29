<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKosTable extends Migration
{
    public function up()
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->increments('id_kos');
            $table->string('nama_kos');
            $table->string('alamat');
            $table->string('harga');
            $table->string('fasilitas');
            $table->text('deskripsi');
            $table->tinyInteger('status_ketersediaan')->default(1)
                  ->comment('1=tersedia,0=tidak');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kos');
    }
}
