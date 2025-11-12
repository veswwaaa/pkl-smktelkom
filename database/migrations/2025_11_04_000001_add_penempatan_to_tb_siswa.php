<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_siswa', function (Blueprint $table) {
            $table->integer('id_dudi')->nullable()->after('jurusan');
            $table->enum('status_penempatan', ['belum', 'ditempatkan', 'selesai'])->default('belum')->after('id_dudi');
            $table->date('tanggal_mulai_pkl')->nullable()->after('status_penempatan');
            $table->date('tanggal_selesai_pkl')->nullable()->after('tanggal_mulai_pkl');

            // Foreign key ke tb_dudi
            $table->foreign('id_dudi')->references('id')->on('tb_dudi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_siswa', function (Blueprint $table) {
            $table->dropForeign(['id_dudi']);
            $table->dropColumn(['id_dudi', 'status_penempatan', 'tanggal_mulai_pkl', 'tanggal_selesai_pkl']);
        });
    }
};
