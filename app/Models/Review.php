<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['registration_id', 'review', 'rating', 'image_path'];

    public function registration()
    {
        return $this->belongsTo(EventRegistration::class, 'event_registration_id');
    }

}
