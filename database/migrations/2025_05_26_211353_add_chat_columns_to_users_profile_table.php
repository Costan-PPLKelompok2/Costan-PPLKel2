<?php
// File: database/migrations/XXXX_XX_XX_XXXXXX_add_chat_columns_to_users_table.php
// (Opsional - jika ingin menambah kolom di tabel users)

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
        Schema::table('users_profile', function (Blueprint $table) {
            // Tambah kolom untuk status online/offline (opsional)
            $table->timestamp('last_seen_at')->nullable()->after('updated_at');
            $table->boolean('is_online')->default(false)->after('last_seen_at');
            
            // Tambah kolom untuk notification preferences (opsional)
            $table->boolean('chat_notifications_enabled')->default(true)->after('is_online');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_profile', function (Blueprint $table) {
            $table->dropColumn(['last_seen_at', 'is_online', 'chat_notifications_enabled']);
        });
    }
};