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
        Schema::table('tb_dudi', function (Blueprint $table) {
            // Change jurusan from varchar to json to support multiple jurusan
            $table->json('jurusan_diterima')->nullable()->after('jobdesk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dudi', function (Blueprint $table) {
            $table->dropColumn('jurusan_diterima');
        });
    }
};
