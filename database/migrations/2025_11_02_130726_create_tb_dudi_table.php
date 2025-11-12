<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_dudi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nama_dudi', 100);
            $table->string('nomor_telpon', 100);
            $table->text('alamat');
            $table->string('person_in_charge', 100);
            $table->string('jurusan', 50)->nullable();
            $table->text('jobdesk')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_dudi');
    }
};
