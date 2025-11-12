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
        Schema::create('tb_siswa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('nis')->unique('nis');
            $table->string('nama');
            $table->string('kelas', 50);
            $table->string('jenis_kelamin', 20);
            $table->string('angkatan', 20);
            $table->string('jurusan', 20);
            $table->string('grade', 20)->nullable();
            $table->string('cv')->nullable();
            $table->string('portofolio')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_siswa');
    }
};
