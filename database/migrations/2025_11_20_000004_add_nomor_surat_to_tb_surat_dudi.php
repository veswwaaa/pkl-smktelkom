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
        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            $table->string('nomor_surat_pengajuan', 100)->nullable()->after('file_surat_pengajuan');
            $table->string('nomor_surat_permohonan', 100)->nullable()->after('file_surat_permohonan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            $table->dropColumn(['nomor_surat_pengajuan', 'nomor_surat_permohonan']);
        });
    }
};
