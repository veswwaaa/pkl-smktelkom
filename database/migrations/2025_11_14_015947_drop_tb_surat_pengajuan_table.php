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
        Schema::dropIfExists('tb_surat_pengajuan');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate jika rollback
        Schema::create('tb_surat_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengajuan_pkl');
            $table->string('file_surat_pengajuan')->nullable();
            $table->timestamp('tanggal_upload_pengajuan')->nullable();
            $table->unsignedBigInteger('uploaded_by_admin')->nullable();
            $table->string('file_surat_balasan')->nullable();
            $table->timestamp('tanggal_upload_balasan')->nullable();
            $table->unsignedBigInteger('uploaded_by_dudi')->nullable();
            $table->enum('status_balasan', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_dudi')->nullable();
            $table->timestamps();
            $table->foreign('id_pengajuan_pkl')->references('id')->on('tb_pengajuan_pkl')->onDelete('cascade');
        });
    }
};
