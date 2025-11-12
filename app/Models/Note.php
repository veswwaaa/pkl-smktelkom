<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'tb_notes';

    protected $fillable = [
        'content',
        'note_date',
        'created_by'
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    /**
     * Relasi ke User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
