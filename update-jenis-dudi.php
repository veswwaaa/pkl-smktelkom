<?php

/**
 * Script untuk update jenis_dudi pada tabel tb_dudi
 * Menandai DUDI yang berasal dari DUDI Mandiri sebagai 'mandiri'
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Update Jenis DUDI Script ===\n\n";

    // Cari semua DUDI yang id-nya ada di tb_dudi_mandiri.id_dudi
    $dudiMandiriIds = DB::table('tb_dudi_mandiri')
        ->whereNotNull('id_dudi')
        ->pluck('id_dudi')
        ->toArray();

    echo "Ditemukan " . count($dudiMandiriIds) . " DUDI dari DUDI Mandiri\n\n";

    if (count($dudiMandiriIds) > 0) {
        // Update jenis_dudi menjadi 'mandiri' untuk DUDI tersebut
        $updated = DB::table('tb_dudi')
            ->whereIn('id', $dudiMandiriIds)
            ->update(['jenis_dudi' => 'mandiri']);

        echo "✅ Berhasil update {$updated} DUDI menjadi jenis 'mandiri'\n\n";

        // Tampilkan detail DUDI yang diupdate
        $dudiList = DB::table('tb_dudi')
            ->whereIn('id', $dudiMandiriIds)
            ->get(['id', 'nama_dudi', 'jenis_dudi']);

        echo "Detail DUDI yang diupdate:\n";
        echo str_repeat('-', 60) . "\n";
        foreach ($dudiList as $dudi) {
            echo "ID: {$dudi->id} | {$dudi->nama_dudi} | Jenis: {$dudi->jenis_dudi}\n";
        }
        echo str_repeat('-', 60) . "\n\n";
    }

    // Tampilkan statistik
    $totalSekolah = DB::table('tb_dudi')->where('jenis_dudi', 'sekolah')->count();
    $totalMandiri = DB::table('tb_dudi')->where('jenis_dudi', 'mandiri')->count();

    echo "\n📊 Statistik DUDI:\n";
    echo "   • DUDI Sekolah: {$totalSekolah}\n";
    echo "   • DUDI Mandiri: {$totalMandiri}\n";
    echo "   • Total: " . ($totalSekolah + $totalMandiri) . "\n\n";

    echo "✅ Script selesai!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
