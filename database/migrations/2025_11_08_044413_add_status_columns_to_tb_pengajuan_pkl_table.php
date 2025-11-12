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
        Schema::table('tb_pengajuan_pkl', function (Blueprint $table) {
            // Status untuk setiap pilihan: pending, approved, rejected
            $table->string('status_pilihan_1')->default('pending')->after('pilihan_aktif');
            $table->string('status_pilihan_2')->default('pending')->after('status_pilihan_1');
            $table->string('status_pilihan_3')->default('pending')->after('status_pilihan_2');

            // Tanggal response dari DUDI
            $table->timestamp('tanggal_response_pilihan_1')->nullable()->after('status_pilihan_3');
            $table->timestamp('tanggal_response_pilihan_2')->nullable()->after('tanggal_response_pilihan_1');
            $table->timestamp('tanggal_response_pilihan_3')->nullable()->after('tanggal_response_pilihan_2');

            // Catatan penolakan dari DUDI (opsional)
            $table->text('catatan_pilihan_1')->nullable()->after('tanggal_response_pilihan_3');
            $table->text('catatan_pilihan_2')->nullable()->after('catatan_pilihan_1');
            $table->text('catatan_pilihan_3')->nullable()->after('catatan_pilihan_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pengajuan_pkl', function (Blueprint $table) {
            $table->dropColumn([
                'status_pilihan_1',
                'status_pilihan_2',
                'status_pilihan_3',
                'tanggal_response_pilihan_1',
                'tanggal_response_pilihan_2',
                'tanggal_response_pilihan_3',
                'catatan_pilihan_1',
                'catatan_pilihan_2',
                'catatan_pilihan_3'
            ]);
        });
    }
};
