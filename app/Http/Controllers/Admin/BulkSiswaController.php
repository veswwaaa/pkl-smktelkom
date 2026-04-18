<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\tb_siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkSiswaController extends Controller
{
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data siswa yang dipilih!');
        }

        try {
            DB::beginTransaction();

            $total = count($ids);

            // Hapus User terkait (karena id_siswa ada di tb_users)
            User::whereIn('id_siswa', $ids)->delete();

            // Hapus Siswa
            tb_siswa::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()->back()->with('success', "$total data siswa berhasil dihapus secara massal!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data massal: ' . $e->getMessage());
        }
    }
}
