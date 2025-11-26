<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the role enum to include 'wali_kelas'
        DB::statement("ALTER TABLE tb_users MODIFY COLUMN role ENUM('admin', 'dudi', 'siswa', 'wali_kelas', '') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE tb_users MODIFY COLUMN role ENUM('admin', 'dudi', 'siswa', '') NOT NULL");
    }
};
