<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $table = 'tb_users';

    protected $fillable = [
        'username',
        'password',
        'role',
        'id_admin',
        'id_dudi',
        'id_siswa'
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(tb_siswa::class, 'id_siswa');
    }

    /**
     * Relasi ke dudi
     */
    public function dudi()
    {
        return $this->belongsTo(tb_dudi::class, 'id_dudi');
    }

    /**
     * Relasi ke admin
     */
    public function admin()
    {
        return $this->belongsTo(tb_admin::class, 'id_admin');
    }
}
