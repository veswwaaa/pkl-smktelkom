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
            $table->enum('grade_kesiswaan', ['tidak_ada', 'ringan', 'sedang', 'berat'])->nullable()->after('status_penempatan');
            $table->enum('grade_kurikulum', ['A', 'B', 'C', 'D', 'E'])->nullable()->after('grade_kesiswaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_siswa', function (Blueprint $table) {
            $table->dropColumn(['grade_kesiswaan', 'grade_kurikulum']);
        });
    }
};
