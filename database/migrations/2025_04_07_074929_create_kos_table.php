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
        // Tabel kos
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // FK dari pemilik kos
            $table->string('nama_kos');
            $table->text('deskripsi');
            $table->string('alamat');
            $table->decimal('harga', 10, 2);
            $table->text('fasilitas'); // bisa disimpan sebagai teks atau JSON
            $table->string('foto')->nullable(); // path gambar
            $table->boolean('status_ketersediaan')
                  ->default(1)
                  ->comment('1 = tersedia, 0 = penuh');
            $table->timestamps();

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabel penghuni
        Schema::create('penghuni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kos_id'); // FK ke tabel kos
            $table->string('nama_penghuni');
            $table->string('no_hp');
            // Tambahkan field lain yang relevan jika perlu
            $table->timestamps();

            // Foreign key
            $table->foreign('kos_id')->references('id')->on('kos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penghuni');
        Schema::dropIfExists('kos');
    }
};
