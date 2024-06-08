<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'judul', 'slug', 'deskripsi', 'tanggal', 'pembicara', 'poster_path', 'harga', 'lokasi'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->slug = Str::slug($event->judul);
        });

        static::updating(function ($event) {
            $event->slug = Str::slug($event->judul);
        });
    }
}
