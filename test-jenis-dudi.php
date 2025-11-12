<?php

/**
 * Test script untuk verify jenis_dudi saat create DUDI
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\tb_dudi;

try {
    echo "=== Test Create DUDI dengan jenis_dudi ===\n\n";

    // Test 1: Create DUDI Sekolah
    echo "Test 1: Create DUDI Sekolah\n";
    $dudiSekolah = tb_dudi::create([
        'nama_dudi' => 'TEST DUDI SEKOLAH',
        'alamat' => 'Test Address',
        'nomor_telpon' => '081234567890',
        'person_in_charge' => 'Test PIC',
        'jenis_dudi' => 'sekolah',
    ]);

    echo "✅ DUDI Sekolah created with ID: {$dudiSekolah->id}\n";
    echo "   jenis_dudi: {$dudiSekolah->jenis_dudi}\n\n";

    // Test 2: Create DUDI Mandiri
    echo "Test 2: Create DUDI Mandiri\n";
    $dudiMandiri = tb_dudi::create([
        'nama_dudi' => 'TEST DUDI MANDIRI',
        'alamat' => 'Test Address',
        'nomor_telpon' => '081234567890',
        'person_in_charge' => 'Test PIC',
        'jenis_dudi' => 'mandiri',
    ]);

    echo "✅ DUDI Mandiri created with ID: {$dudiMandiri->id}\n";
    echo "   jenis_dudi: {$dudiMandiri->jenis_dudi}\n\n";

    // Verify dari database
    echo "Verifikasi dari database:\n";
    $verifySekolah = tb_dudi::find($dudiSekolah->id);
    $verifyMandiri = tb_dudi::find($dudiMandiri->id);

    echo "DUDI Sekolah (ID {$verifySekolah->id}): jenis_dudi = '{$verifySekolah->jenis_dudi}'\n";
    echo "DUDI Mandiri (ID {$verifyMandiri->id}): jenis_dudi = '{$verifyMandiri->jenis_dudi}'\n\n";

    // Cleanup - hapus data test
    echo "Cleanup test data...\n";
    $dudiSekolah->delete();
    $dudiMandiri->delete();
    echo "✅ Test data deleted\n\n";

    if ($verifySekolah->jenis_dudi === 'sekolah' && $verifyMandiri->jenis_dudi === 'mandiri') {
        echo "✅ SEMUA TEST PASSED! Field jenis_dudi berfungsi dengan baik.\n";
    } else {
        echo "❌ TEST FAILED! Field jenis_dudi tidak tersimpan dengan benar.\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
