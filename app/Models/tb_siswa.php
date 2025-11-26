<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tb_siswa extends Model
{
    //
    protected $table = 'tb_siswa';

    protected $fillable = [
        'nis',
        'nama',
        'kelas',
        'jenis_kelamin',
        'angkatan',
        'jurusan',
        'id_dudi',
        'status_penempatan',
        'tanggal_mulai_pkl',
        'tanggal_selesai_pkl',
        'grade_kesiswaan',
        'grade_kurikulum'
    ];

    // Relasi ke DUDI
    public function dudi()
    {
        return $this->belongsTo(\App\Models\tb_dudi::class, 'id_dudi', 'id');
    }

    // Relasi ke DokumenSiswa
    public function dokumen()
    {
        return $this->hasOne(\App\Models\DokumenSiswa::class, 'id_siswa', 'id');
    }
}
