<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewsToKosTable extends Migration
{
    public function up()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->unsignedBigInteger('views')->default(0); // Tambahkan kolom views
        });
    }

    public function down()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropColumn('views');
        });
    }
}

//