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
            // Rename catatan columns
            $table->renameColumn('catatan_dudi_pengajuan', 'catatan_balasan_pengajuan');
            $table->renameColumn('catatan_dudi_permohonan', 'catatan_balasan_permohonan');
        });

        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            // Add status columns for balasan
            $table->string('status_balasan_pengajuan', 50)->nullable()->after('file_balasan_pengajuan');
            $table->string('status_balasan_permohonan', 50)->nullable()->after('file_balasan_permohonan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            $table->dropColumn(['status_balasan_pengajuan', 'status_balasan_permohonan']);
        });

        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            $table->renameColumn('catatan_balasan_pengajuan', 'catatan_dudi_pengajuan');
            $table->renameColumn('catatan_balasan_permohonan', 'catatan_dudi_permohonan');
        });
    }
};
