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
        Schema::create('dokumen_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_siswa');

            // CV & Portofolio
            $table->string('file_cv')->nullable();
            $table->string('file_portofolio')->nullable();
            $table->timestamp('tanggal_upload_cv_portofolio')->nullable();

            // Surat Pernyataan dari Admin
            $table->string('file_surat_pernyataan')->nullable();
            $table->timestamp('tanggal_kirim_surat_pernyataan')->nullable();
            $table->string('nomor_surat_pernyataan')->nullable();

            // Eviden dari Siswa (jawaban + foto dengan ortu)
            $table->text('jawaban_surat_pernyataan')->nullable();
            $table->string('file_foto_dengan_ortu')->nullable();
            $table->timestamp('tanggal_upload_eviden')->nullable();

            // Surat Tugas dari Admin
            $table->string('file_surat_tugas')->nullable();
            $table->timestamp('tanggal_kirim_surat_tugas')->nullable();
            $table->string('nomor_surat_tugas')->nullable();

            // Status tracking
            $table->enum('status_cv_portofolio', ['belum', 'sudah'])->default('belum');
            $table->enum('status_surat_pernyataan', ['belum', 'terkirim'])->default('belum');
            $table->enum('status_eviden', ['belum', 'sudah'])->default('belum');
            $table->enum('status_surat_tugas', ['belum', 'terkirim'])->default('belum');

            $table->timestamps();

            // Foreign key
            $table->foreign('id_siswa')->references('id')->on('tb_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_siswa');
    }
};
