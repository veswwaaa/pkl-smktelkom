<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDudi extends Model
{
    protected $table = 'tb_surat_dudi';

    protected $fillable = [
        'id_dudi',
        'file_surat_pengajuan',
        'tanggal_upload_pengajuan',
        'uploaded_by_admin',
        'file_surat_balasan',
        'tanggal_upload_balasan',
        'catatan_admin_pengajuan',
        'catatan_dudi_pengajuan',
        'file_surat_permohonan',
        'tanggal_upload_permohonan',
        'file_balasan_permohonan',
        'tanggal_upload_balasan_permohonan',
        'catatan_admin_permohonan',
        'catatan_dudi_permohonan'
    ];

    protected $casts = [
        'tanggal_upload_pengajuan' => 'datetime',
        'tanggal_upload_balasan' => 'datetime',
        'tanggal_upload_permohonan' => 'datetime',
        'tanggal_upload_balasan_permohonan' => 'datetime',
    ];

    // Relation ke tb_dudi
    public function dudi()
    {
        return $this->belongsTo(tb_dudi::class, 'id_dudi', 'id');
    }

    // Relation ke admin yang upload (jika perlu)
    public function admin()
    {
        return $this->belongsTo(tb_admin::class, 'uploaded_by_admin', 'id');
    }
}
