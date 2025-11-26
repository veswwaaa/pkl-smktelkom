<?php

// Script untuk membuat akun Wali Kelas
// Jalankan: php scripts/create_wali_kelas.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\tb_admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Cek apakah user sudah ada
    $existingUser = User::where('username', 'walikelas1')->first();
    if ($existingUser) {
        echo "❌ User 'walikelas1' sudah ada!\n";
        echo "Gunakan username lain atau hapus user yang ada terlebih dahulu.\n";
        exit(1);
    }

    // Buat admin
    $admin = new tb_admin();
    $admin->nama_admin = 'Wali Kelas Test';
    $admin->no_telpon = '08123456789';
    $admin->alamat = 'SMK Telkom Banjarbaru';
    $admin->save();

    echo "✅ Admin created with ID: {$admin->id}\n";

    // Buat user wali kelas
    $user = new User();
    $user->username = 'walikelas1';
    $user->password = Hash::make('walikelas123');
    $user->role = 'wali_kelas';
    $user->id_admin = $admin->id;
    $user->id_dudi = null;
    $user->id_siswa = null;
    $user->save();

    echo "✅ User wali kelas created successfully!\n\n";
    echo "═══════════════════════════════════════\n";
    echo "📋 KREDENSIAL LOGIN WALI KELAS\n";
    echo "═══════════════════════════════════════\n";
    echo "Username: walikelas1\n";
    echo "Password: walikelas123\n";
    echo "Role: wali_kelas\n";
    echo "═══════════════════════════════════════\n";
    echo "\n🌐 Login di: http://localhost/pkl-smktelkom/login\n";
    echo "\n⚠️  PENTING: Ganti password setelah login pertama!\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
