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
            // Ubah kolom pilihan_aktif dari enum menjadi string
            $table->string('pilihan_aktif', 100)->default('1')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pengajuan_pkl', function (Blueprint $table) {
            // Kembalikan ke enum jika rollback
            $table->enum('pilihan_aktif', ['1', '2', '3'])->default('1')->change();
        });
    }
};
