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
            // Kolom untuk surat permohonan
            $table->string('file_surat_permohonan')->nullable()->after('catatan_dudi');
            $table->timestamp('tanggal_upload_permohonan')->nullable()->after('file_surat_permohonan');
            $table->string('file_balasan_permohonan')->nullable()->after('tanggal_upload_permohonan');
            $table->timestamp('tanggal_upload_balasan_permohonan')->nullable()->after('file_balasan_permohonan');
            $table->text('catatan_admin_permohonan')->nullable()->after('tanggal_upload_balasan_permohonan');
            $table->text('catatan_balasan_permohonan')->nullable()->after('catatan_admin_permohonan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            $table->dropColumn([
                'file_surat_permohonan',
                'tanggal_upload_permohonan',
                'file_balasan_permohonan',
                'tanggal_upload_balasan_permohonan',
                'catatan_admin_permohonan',
                'catatan_balasan_permohonan'
            ]);
        });
    }
};
