<?php

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

if (!function_exists('logActivity')) {
    /**
     * Log aktivitas user di sistem
     * 
     * @param string $type Type aktivitas (login, create, update, delete, info, warning, success)
     * @param string $title Judul aktivitas
     * @param string $description Deskripsi detail aktivitas
     * @param int|null $userId ID user (optional, akan auto-detect dari session)
     * @return Activity
     */
    function logActivity($type, $title, $description, $userId = null)
    {
        // Ambil user_id dari session jika tidak disediakan
        if ($userId === null && Session::has('loginId')) {
            $userId = Session::get('loginId');
        }

        // Ambil username dan nama lengkap
        $username = 'System';
        $fullName = null;

        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $username = $user->username;

                // Jika user adalah siswa, ambil nama lengkap dari relasi
                if ($user->role === 'siswa' && $user->siswa) {
                    $fullName = $user->siswa->nama;
                }
                // Jika user adalah dudi, ambil nama dari relasi
                elseif ($user->role === 'dudi' && $user->dudi) {
                    $fullName = $user->dudi->nama_instansi;
                }
                // Jika user adalah admin, ambil nama dari relasi
                elseif ($user->role === 'admin' && $user->admin) {
                    $fullName = $user->admin->name;
                }
            }
        }

        // Format username untuk display
        $displayName = $username;
        if ($fullName) {
            $displayName = "{$username} ({$fullName})";
        }

        // Simpan activity log
        return Activity::create([
            'user_id' => $userId,
            'username' => $displayName,
            'type' => $type,
            'title' => $title,
            'description' => $description,
        ]);
    }
}

if (!function_exists('getRecentActivities')) {
    /**
     * Ambil 10 aktivitas terbaru hari ini
     * 
     * @param int $limit Jumlah aktivitas yang ditampilkan (default: 10)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getRecentActivities($limit = 10)
    {
        return Activity::today()
            ->latest()
            ->take($limit)
            ->get();
    }
}
