<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPkl extends Model
{
    protected $table = 'tb_pengajuan_pkl';

    protected $fillable = [
        'id_siswa',
        'id_dudi_pilihan_1',
        'id_dudi_mandiri_pilihan_1',
        'id_dudi_pilihan_2',
        'id_dudi_mandiri_pilihan_2',
        'id_dudi_pilihan_3',
        'id_dudi_mandiri_pilihan_3',
        'pilihan_aktif',
        'status',
        'status_pilihan_1',
        'status_pilihan_2',
        'status_pilihan_3',
        'tanggal_response_pilihan_1',
        'tanggal_response_pilihan_2',
        'tanggal_response_pilihan_3',
        'catatan_pilihan_1',
        'catatan_pilihan_2',
        'catatan_pilihan_3',
        'tanggal_pengajuan',
        'cv_file',
        'surat_balasan'
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(tb_siswa::class, 'id_siswa', 'id');
    }

    // Relasi ke DUDI Sekolah
    public function dudiPilihan1()
    {
        return $this->belongsTo(tb_dudi::class, 'id_dudi_pilihan_1', 'id');
    }

    public function dudiPilihan2()
    {
        return $this->belongsTo(tb_dudi::class, 'id_dudi_pilihan_2', 'id');
    }

    public function dudiPilihan3()
    {
        return $this->belongsTo(tb_dudi::class, 'id_dudi_pilihan_3', 'id');
    }

    // Relasi ke DUDI Mandiri
    public function dudiMandiriPilihan1()
    {
        return $this->belongsTo(DudiMandiri::class, 'id_dudi_mandiri_pilihan_1', 'id');
    }

    public function dudiMandiriPilihan2()
    {
        return $this->belongsTo(DudiMandiri::class, 'id_dudi_mandiri_pilihan_2', 'id');
    }

    public function dudiMandiriPilihan3()
    {
        return $this->belongsTo(DudiMandiri::class, 'id_dudi_mandiri_pilihan_3', 'id');
    }
}
