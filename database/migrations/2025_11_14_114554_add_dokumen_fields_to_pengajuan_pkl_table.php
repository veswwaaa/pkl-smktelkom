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
        Schema::table('tb_pengajuan_pkl', function (Blueprint $table) {
            $table->string('cv_file')->nullable()->after('status');
            $table->string('surat_balasan')->nullable()->after('cv_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pengajuan_pkl', function (Blueprint $table) {
            $table->dropColumn(['cv_file', 'surat_balasan']);
        });
    }
};
