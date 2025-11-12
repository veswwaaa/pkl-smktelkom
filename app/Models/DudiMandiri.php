<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DudiMandiri extends Model
{
    protected $table = 'tb_dudi_mandiri';

    protected $fillable = [
        'id_siswa',
        'id_dudi',
        'nama_dudi',
        'nomor_telepon',
        'person_in_charge',
        'alamat',
        'status'
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(tb_siswa::class, 'id_siswa', 'id');
    }

    // Relasi ke DUDI (setelah di-approve)
    public function dudi()
    {
        return $this->belongsTo(tb_dudi::class, 'id_dudi', 'id');
    }
}