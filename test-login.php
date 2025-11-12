<?php
// Test login DUDI
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST LOGIN DUDI ===\n\n";

// Ambil user DUDI berdasarkan username
$testUsername = 'ptmtp'; // Username terbaru yang baru dibuat

$user = DB::table('tb_users')
    ->where('username', $testUsername)
    ->where('role', 'dudi')
    ->first();

if ($user) {
    echo "Username: {$user->username}\n";
    echo "Role: {$user->role}\n";
    echo "ID DUDI: {$user->id_dudi}\n";
    echo "Password Hash: {$user->password}\n\n";

    // Test password
    $testPassword = 'dudi123';
    $isMatch = Hash::check($testPassword, $user->password);

    echo "Test Password: {$testPassword}\n";
    echo "Result: " . ($isMatch ? "✅ MATCH - Login should work!" : "❌ NOT MATCH - Login will fail!") . "\n\n";

    if ($isMatch) {
        // Cek DUDI data
        $dudi = DB::table('tb_dudi')->where('id', $user->id_dudi)->first();
        if ($dudi) {
            echo "DUDI Data:\n";
            echo "  - Nama: {$dudi->nama_dudi}\n";
            echo "  - Alamat: {$dudi->alamat}\n";
            echo "  - Telepon: {$dudi->nomor_telpon}\n";
            echo "  - PIC: {$dudi->person_in_charge}\n";
        }
    }
} else {
    echo "❌ Tidak ada user DUDI ditemukan!\n";
}
