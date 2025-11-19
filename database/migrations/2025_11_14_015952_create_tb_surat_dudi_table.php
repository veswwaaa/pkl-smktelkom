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
        Schema::create('tb_surat_dudi', function (Blueprint $table) {
            $table->id();
            $table->integer('id_dudi')->unsigned(); // FK ke tb_dudi (INTEGER)
            $table->string('file_surat_pengajuan')->nullable(); // Surat dari admin ke DUDI (untuk semua siswa)
            $table->timestamp('tanggal_upload_pengajuan')->nullable();
            $table->unsignedBigInteger('uploaded_by_admin')->nullable(); // ID admin yang upload
            $table->string('file_surat_balasan')->nullable(); // Surat balasan dari DUDI (bisa untuk semua atau per siswa)
            $table->timestamp('tanggal_upload_balasan')->nullable();
            $table->text('catatan_admin')->nullable(); // Catatan saat upload surat pengajuan
            $table->text('catatan_dudi')->nullable(); // Catatan dari DUDI saat upload balasan
            $table->timestamps();

            // Foreign keys - sesuaikan dengan tipe integer di tb_dudi
            $table->foreign('id_dudi')->references('id')->on('tb_dudi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_surat_dudi');
    }
};
