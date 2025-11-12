<?php

/**
 * Script untuk update username PT MTP dari "ptmtp" menjadi "pt mtp"
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "=== Fix Username PT MTP ===\n\n";

    // Cari user dengan username "ptmtp"
    $user = DB::table('tb_users')->where('username', 'ptmtp')->first();

    if (!$user) {
        echo "❌ User dengan username 'ptmtp' tidak ditemukan.\n";
        exit;
    }

    echo "User ditemukan:\n";
    echo "  ID: {$user->id}\n";
    echo "  Username Lama: {$user->username}\n";
    echo "  ID DUDI: {$user->id_dudi}\n\n";

    // Cari nama DUDI
    $dudi = DB::table('tb_dudi')->where('id', $user->id_dudi)->first();
    if ($dudi) {
        echo "  Nama DUDI: {$dudi->nama_dudi}\n\n";
    }

    // Update username
    $updated = DB::table('tb_users')
        ->where('id', $user->id)
        ->update(['username' => 'pt mtp']);

    if ($updated) {
        echo "✅ Username berhasil diupdate!\n\n";

        // Verifikasi
        $updatedUser = DB::table('tb_users')->where('id', $user->id)->first();
        echo "Username Baru: {$updatedUser->username}\n\n";

        echo "📝 Kredensial Login:\n";
        echo "   Username: pt mtp\n";
        echo "   Password: dudi123\n\n";

        echo "✅ Selesai! DUDI PT MTP sekarang bisa login dengan username 'pt mtp'\n";
    } else {
        echo "❌ Gagal update username.\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
