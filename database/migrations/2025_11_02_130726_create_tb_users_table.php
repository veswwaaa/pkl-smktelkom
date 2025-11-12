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
        Schema::create('tb_users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('username', 100)->unique('username');
            $table->string('password', 100);
            $table->enum('role', ['admin', 'dudi', 'siswa', '']);
            $table->integer('id_admin')->nullable()->index('id_admin');
            $table->integer('id_dudi')->nullable()->index('dudi');
            $table->integer('id_siswa')->nullable()->index('siswa');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_users');
    }
};
