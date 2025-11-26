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
        Schema::table('dokumen_siswa', function (Blueprint $table) {
            $table->string('file_surat_pernyataan_siswa')->nullable()->after('tanggal_kirim_surat_pernyataan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_siswa', function (Blueprint $table) {
            $table->dropColumn('file_surat_pernyataan_siswa');
        });
    }
};
