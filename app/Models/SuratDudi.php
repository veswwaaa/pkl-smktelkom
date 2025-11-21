<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDudi extends Model
{
    protected $table = 'tb_surat_dudi';

    protected $fillable = [
        'id_dudi',
        'file_surat_pengajuan',
        'nomor_surat_pengajuan',
        'tanggal_upload_pengajuan',
        'uploaded_by_admin',
        'file_balasan_pengajuan',
        'tanggal_upload_balasan_pengajuan',
        'status_balasan_pengajuan',
        'catatan_admin_pengajuan',
        'catatan_balasan_pengajuan',
        'file_surat_permohonan',
        'nomor_surat_permohonan',
        'tanggal_upload_permohonan',
        'file_balasan_permohonan',
        'tanggal_upload_balasan_permohonan',
        'status_balasan_permohonan',
        'catatan_admin_permohonan',
        'catatan_balasan_permohonan'
    ];

    protected $casts = [
        'tanggal_upload_pengajuan' => 'datetime',
        'tanggal_upload_balasan_pengajuan' => 'datetime',
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
