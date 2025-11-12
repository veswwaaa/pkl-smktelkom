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
        Schema::create('tb_dudi_mandiri', function (Blueprint $table) {
            $table->id();
            $table->integer('id_siswa')->unsigned();
            $table->string('nama_dudi');
            $table->string('nomor_telepon');
            $table->string('person_in_charge');
            $table->text('alamat');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Index untuk id_siswa
            $table->index('id_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_dudi_mandiri');
    }
};
