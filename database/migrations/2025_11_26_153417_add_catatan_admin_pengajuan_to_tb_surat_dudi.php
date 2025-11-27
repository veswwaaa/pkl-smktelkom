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
            $table->text('catatan_admin_pengajuan')->nullable()->after('tanggal_upload_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_surat_dudi', function (Blueprint $table) {
            $table->dropColumn('catatan_admin_pengajuan');
        });
    }
};
