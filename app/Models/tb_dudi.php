<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tb_dudi extends Model
{
  protected $table = 'tb_dudi';

  protected $fillable = [
    'nama_dudi',
    'nomor_telpon',
    'alamat',
    'person_in_charge',
    'jurusan',
    'jobdesk',
    'jenis_dudi',
    'jurusan_diterima',
    'data_penerimaan_pkl',
  ];

  protected $casts = [
    'jurusan_diterima' => 'array',
    'data_penerimaan_pkl' => 'array',
  ];
}
