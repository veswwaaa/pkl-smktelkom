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
        Schema::create('tb_pengajuan_pkl', function (Blueprint $table) {
            $table->id();
            $table->integer('id_siswa')->unsigned();

            // Pilihan 1 (bisa DUDI Sekolah atau DUDI Mandiri)
            $table->integer('id_dudi_pilihan_1')->unsigned()->nullable();
            $table->unsignedBigInteger('id_dudi_mandiri_pilihan_1')->nullable();

            // Pilihan 2 (bisa DUDI Sekolah atau DUDI Mandiri)
            $table->integer('id_dudi_pilihan_2')->unsigned()->nullable();
            $table->unsignedBigInteger('id_dudi_mandiri_pilihan_2')->nullable();

            // Pilihan 3 (bisa DUDI Sekolah atau DUDI Mandiri)
            $table->integer('id_dudi_pilihan_3')->unsigned()->nullable();
            $table->unsignedBigInteger('id_dudi_mandiri_pilihan_3')->nullable();

            $table->enum('pilihan_aktif', ['1', '2', '3'])->default('1'); // Pilihan mana yang sedang diproses
            $table->enum('status', ['pending', 'diproses', 'approved', 'rejected'])->default('pending');
            $table->date('tanggal_pengajuan');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Indexes
            $table->index('id_siswa');
            $table->index('id_dudi_pilihan_1');
            $table->index('id_dudi_pilihan_2');
            $table->index('id_dudi_pilihan_3');
            $table->index('id_dudi_mandiri_pilihan_1');
            $table->index('id_dudi_mandiri_pilihan_2');
            $table->index('id_dudi_mandiri_pilihan_3');
        });
    }    /**
         * Reverse the migrations.
         */
    public function down(): void
    {
        Schema::dropIfExists('tb_pengajuan_pkl');
    }
};
