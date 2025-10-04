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
        'jurusan'
    ];
}
