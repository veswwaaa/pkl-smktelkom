<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_admin extends Model
{
    use HasFactory;
    protected $table = 'tb_admin';

    protected $fillable = [
        'nip',
        'kelas',
        'nama_admin',
        'no_telpon',
        'alamat',
    ];
}
