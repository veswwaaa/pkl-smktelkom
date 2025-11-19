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
        Schema::create('tb_surat_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengajuan_pkl'); // FK ke tb_pengajuan_pkl
            $table->string('file_surat_pengajuan')->nullable(); // Surat dari admin ke DUDI
            $table->timestamp('tanggal_upload_pengajuan')->nullable();
            $table->unsignedBigInteger('uploaded_by_admin')->nullable(); // ID admin yang upload
            $table->string('file_surat_balasan')->nullable(); // Surat balasan dari DUDI
            $table->timestamp('tanggal_upload_balasan')->nullable();
            $table->unsignedBigInteger('uploaded_by_dudi')->nullable(); // ID DUDI yang upload
            $table->enum('status_balasan', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable(); // Catatan saat upload surat pengajuan
            $table->text('catatan_dudi')->nullable(); // Catatan dari DUDI saat upload balasan
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_pengajuan_pkl')->references('id')->on('tb_pengajuan_pkl')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_surat_pengajuan');
    }
};
