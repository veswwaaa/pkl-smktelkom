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
        // Safely rename catatan columns only if source columns exist
        if (Schema::hasColumn('tb_surat_dudi', 'catatan_dudi_pengajuan') || Schema::hasColumn('tb_surat_dudi', 'catatan_dudi_permohonan')) {
            Schema::table('tb_surat_dudi', function (Blueprint $table) {
                if (Schema::hasColumn('tb_surat_dudi', 'catatan_dudi_pengajuan') && !Schema::hasColumn('tb_surat_dudi', 'catatan_balasan_pengajuan')) {
                    $table->renameColumn('catatan_dudi_pengajuan', 'catatan_balasan_pengajuan');
                }
                if (Schema::hasColumn('tb_surat_dudi', 'catatan_dudi_permohonan') && !Schema::hasColumn('tb_surat_dudi', 'catatan_balasan_permohonan')) {
                    $table->renameColumn('catatan_dudi_permohonan', 'catatan_balasan_permohonan');
                }
            });
        }

        // Add status columns for balasan if they don't already exist
        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_surat_dudi', 'status_balasan_pengajuan')) {
                if (Schema::hasColumn('tb_surat_dudi', 'file_balasan_pengajuan')) {
                    $table->string('status_balasan_pengajuan', 50)->nullable()->after('file_balasan_pengajuan');
                } else {
                    $table->string('status_balasan_pengajuan', 50)->nullable();
                }
            }
            if (!Schema::hasColumn('tb_surat_dudi', 'status_balasan_permohonan')) {
                if (Schema::hasColumn('tb_surat_dudi', 'file_balasan_permohonan')) {
                    $table->string('status_balasan_permohonan', 50)->nullable()->after('file_balasan_permohonan');
                } else {
                    $table->string('status_balasan_permohonan', 50)->nullable();
                }
            }
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
