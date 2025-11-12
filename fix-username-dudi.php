<?php
// Script untuk membersihkan username DUDI yang bermasalah
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PERBAIKI USERNAME DUDI YANG BERMASALAH ===\n\n";

// Ambil semua user DUDI
$users = DB::table('tb_users')->where('role', 'dudi')->get();

$fixed = 0;
$skipped = 0;

foreach ($users as $user) {
    // Cek apakah username mengandung spasi atau karakter khusus
    if (preg_match('/\s/', $user->username) || preg_match('/[^a-z0-9]/', $user->username)) {
        echo "MASALAH: Username '{$user->username}' (ID: {$user->id})\n";

        // Buat username baru yang bersih
        $newUsername = $user->username;

        // Hapus semua whitespace
        $newUsername = preg_replace('/\s+/', '', $newUsername);

        // Hapus karakter khusus, hanya biarkan huruf dan angka
        $newUsername = preg_replace('/[^a-zA-Z0-9]/', '', $newUsername);

        // Convert ke lowercase
        $newUsername = strtolower($newUsername);

        if (empty($newUsername)) {
            echo "  ❌ SKIP: Username kosong setelah dibersihkan\n\n";
            $skipped++;
            continue;
        }

        // Cek apakah username baru sudah digunakan
        $counter = 1;
        $originalNew = $newUsername;
        while (DB::table('tb_users')->where('username', $newUsername)->where('id', '!=', $user->id)->exists()) {
            $newUsername = $originalNew . $counter;
            $counter++;
        }

        // Update username
        DB::table('tb_users')->where('id', $user->id)->update(['username' => $newUsername]);

        echo "  ✅ DIPERBAIKI: '{$user->username}' → '{$newUsername}'\n\n";
        $fixed++;
    }
}

echo "=== SELESAI ===\n";
echo "Diperbaiki: {$fixed} username\n";
echo "Dilewati: {$skipped} username\n";

// Tampilkan semua username DUDI setelah perbaikan
echo "\n=== DAFTAR USERNAME DUDI (SETELAH PERBAIKAN) ===\n";
$users = DB::table('tb_users')->where('role', 'dudi')->orderBy('id', 'desc')->get(['id', 'username', 'id_dudi']);
foreach ($users as $u) {
    echo "ID: {$u->id}, Username: {$u->username}, DUDI ID: {$u->id_dudi}\n";
}
