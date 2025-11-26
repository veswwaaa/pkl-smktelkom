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
        Schema::table('tb_dudi', function (Blueprint $table) {
            $table->json('data_penerimaan_pkl')->nullable()->after('jurusan_diterima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dudi', function (Blueprint $table) {
            $table->dropColumn('data_penerimaan_pkl');
        });
    }
};
