<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPengajuan extends Model
{
    protected $table = 'tb_surat_pengajuan';

    protected $fillable = [
        'id_pengajuan_pkl',
        'file_surat_pengajuan',
        'tanggal_upload_pengajuan',
        'uploaded_by_admin',
        'file_surat_balasan',
        'tanggal_upload_balasan',
        'uploaded_by_dudi',
        'status_balasan',
        'catatan_admin',
        'catatan_dudi'
    ];

    protected $casts = [
        'tanggal_upload_pengajuan' => 'datetime',
        'tanggal_upload_balasan' => 'datetime'
    ];

    // Relasi ke PengajuanPkl
    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'id_pengajuan_pkl');
    }

    // Relasi ke Admin yang upload
    public function admin()
    {
        return $this->belongsTo(tb_admin::class, 'uploaded_by_admin');
    }

    // Relasi ke DUDI yang upload balasan
    public function dudi()
    {
        return $this->belongsTo(tb_dudi::class, 'uploaded_by_dudi');
    }
}
