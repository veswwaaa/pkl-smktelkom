<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenSiswa extends Model
{
    protected $table = 'dokumen_siswa';

    protected $fillable = [
        'id_siswa',
        'file_cv',
        'file_portofolio',
        'tanggal_upload_cv_portofolio',
        'file_surat_pernyataan',
        'file_surat_pernyataan_siswa',
        'tanggal_kirim_surat_pernyataan',
        'nomor_surat_pernyataan',
        'jawaban_surat_pernyataan',
        'file_foto_dengan_ortu',
        'tanggal_upload_eviden',
        'file_surat_tugas',
        'tanggal_kirim_surat_tugas',
        'nomor_surat_tugas',
        'status_cv_portofolio',
        'status_surat_pernyataan',
        'status_eviden',
        'status_surat_tugas'
    ];

    protected $casts = [
        'tanggal_upload_cv_portofolio' => 'datetime',
        'tanggal_kirim_surat_pernyataan' => 'datetime',
        'tanggal_upload_eviden' => 'datetime',
        'tanggal_kirim_surat_tugas' => 'datetime',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(\App\Models\tb_siswa::class, 'id_siswa', 'id');
    }
}
