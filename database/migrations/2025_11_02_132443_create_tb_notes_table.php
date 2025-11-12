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
        Schema::create('tb_notes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('content');
            $table->date('note_date');
            $table->integer('created_by');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            // Foreign key ke tb_users
            $table->foreign('created_by')->references('id')->on('tb_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_notes');
    }
};
