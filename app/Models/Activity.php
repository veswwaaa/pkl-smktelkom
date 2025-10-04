<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'user_id',
        'username',
        'type',
        'title',
        'description',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope untuk filter aktivitas hari ini saja
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Scope untuk ambil aktivitas terbaru
    public function scopeLatest10($query)
    {
        return $query->latest()->take(10);
    }
}
