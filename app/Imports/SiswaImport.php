<?php

namespace App\Imports;

use App\Models\User;
use App\Models\tb_siswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SiswaImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip jika semua field kosong (baris kosong di Excel)
        $allEmpty = true;
        foreach ($row as $value) {
            if (!empty($value)) {
                $allEmpty = false;
                break;
            }
        }

        if ($allEmpty) {
            return null;
        }

        // Cek apakah header Excel sesuai format
        $requiredHeaders = ['nis', 'nama', 'kelas', 'jenis_kelamin', 'angkatan', 'jurusan'];
        foreach ($requiredHeaders as $header) {
            if (!array_key_exists($header, $row)) {
                throw new \Exception("❌ FORMAT EXCEL SALAH! Header yang dibutuhkan: nis, nama, kelas, jenis_kelamin, angkatan, jurusan. Pastikan nama kolom persis sama (huruf kecil semua).");
            }
        }

        // Skip jika field penting kosong
        if (empty($row['nis']) || empty($row['nama'])) {
            return null;
        }

        // Validasi NIS
        if (!is_numeric($row['nis'])) {
            throw new \Exception("❌ NIS '{$row['nis']}' harus berupa angka saja (tanpa huruf atau karakter lain).");
        }

        if (strlen($row['nis']) != 9) {
            throw new \Exception("❌ NIS '{$row['nis']}' harus tepat 9 digit angka. Contoh: 123456789");
        }

        // Validasi Jenis Kelamin
        if (!in_array($row['jenis_kelamin'], ['Laki-laki', 'Perempuan'])) {
            throw new \Exception("❌ Jenis kelamin '{$row['jenis_kelamin']}' tidak valid! Harus 'Laki-laki' atau 'Perempuan' (huruf L dan P kapital).");
        }

        // Validasi field kosong
        if (empty($row['kelas'])) {
            throw new \Exception("❌ Kelas tidak boleh kosong untuk siswa '{$row['nama']}'");
        }

        if (empty($row['angkatan'])) {
            throw new \Exception("❌ Angkatan tidak boleh kosong untuk siswa '{$row['nama']}'");
        }

        if (empty($row['jurusan'])) {
            throw new \Exception("❌ Jurusan tidak boleh kosong untuk siswa '{$row['nama']}'");
        }

        //ngecek nis nya udah ada apa belum
        $existingSiswa = tb_siswa::where('nis', $row['nis'])->first();
        if ($existingSiswa) {
            return null;  //kalau nis nya sudah ada jadi fungsi yang ini di skip
        }

        //ngebuat data siswa baru
        $siswa = tb_siswa::create([
            'nis' => trim($row['nis']),
            'nama' => trim($row['nama']),
            'kelas' => trim($row['kelas']),
            'jenis_kelamin' => trim($row['jenis_kelamin']),
            'angkatan' => trim($row['angkatan']),
            'jurusan' => trim($row['jurusan']),
        ]);


        //ngebuat password otomatis
        $password = 'dummy@' . $row['nis'];


        //buat akun usernya untuk siswa login
        User::create([
            'username' => $row['nis'],
            'password' => Hash::make($password),
            'role' => 'siswa',
            'id_admin' => null,
            'id_dudi' => null,
            'id_siswa' => $siswa->id,
        ]);

        return $siswa;
    }
}
