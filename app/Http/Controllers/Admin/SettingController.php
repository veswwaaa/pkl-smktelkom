<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Update global PKL dates.
     */
    public function updatePklDates(Request $request)
    {
        $request->validate([
            'tanggal_mulai_pkl' => 'required|date',
            'tanggal_selesai_pkl' => 'required|date|after:tanggal_mulai_pkl'
        ]);

        try {
            DB::table('settings')->updateOrInsert(
                ['key' => 'tanggal_mulai_pkl'],
                ['value' => $request->tanggal_mulai_pkl, 'updated_at' => now()]
            );

            DB::table('settings')->updateOrInsert(
                ['key' => 'tanggal_selesai_pkl'],
                ['value' => $request->tanggal_selesai_pkl, 'updated_at' => now()]
            );

            // Opsional: Perbarui semua siswa yang sudah ditempatkan agar tetap sinkron
            \App\Models\tb_siswa::where('status_penempatan', 'ditempatkan')
                ->update([
                    'tanggal_mulai_pkl' => $request->tanggal_mulai_pkl,
                    'tanggal_selesai_pkl' => $request->tanggal_selesai_pkl
                ]);

            return redirect()->back()->with('success', 'Periode PKL global berhasil diperbarui dan diterapkan ke semua siswa!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui periode PKL: ' . $e->getMessage());
        }
    }
}
