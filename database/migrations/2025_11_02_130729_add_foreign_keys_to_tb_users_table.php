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
        Schema::table('tb_users', function (Blueprint $table) {
            $table->foreign(['id_admin'], 'admin')->references(['id'])->on('tb_admin')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_dudi'], 'dudi')->references(['id'])->on('tb_dudi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['id_siswa'], 'siswa')->references(['id'])->on('tb_siswa')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_users', function (Blueprint $table) {
            $table->dropForeign('admin');
            $table->dropForeign('dudi');
            $table->dropForeign('siswa');
        });
    }
};
