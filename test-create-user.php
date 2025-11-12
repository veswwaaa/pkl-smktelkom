<?php
// Test create user DUDI baru
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TEST CREATE USER DUDI ===\n\n";

$testPassword = 'dudi123';
$testUsername = 'testdudi' . time();

echo "Creating user:\n";
echo "Username: {$testUsername}\n";
echo "Password: {$testPassword}\n\n";

// Buat hash
$hash = Hash::make($testPassword);
echo "Hash: {$hash}\n\n";

// Test hash
$isMatch = Hash::check($testPassword, $hash);
echo "Hash check BEFORE save: " . ($isMatch ? "✅ MATCH" : "❌ NOT MATCH") . "\n\n";

// Buat user
$user = User::create([
    'username' => $testUsername,
    'password' => $hash,
    'role' => 'dudi',
    'id_dudi' => 10, // ID DUDI yang valid
]);

echo "User created with ID: {$user->id}\n\n";

// Ambil kembali dari database
$userFromDb = User::find($user->id);
echo "Password from DB: {$userFromDb->password}\n\n";

// Test apakah password match
$isMatchDb = Hash::check($testPassword, $userFromDb->password);
echo "Hash check AFTER save: " . ($isMatchDb ? "✅ MATCH" : "❌ NOT MATCH") . "\n\n";

// Hapus user test
$user->delete();
echo "Test user deleted.\n";
