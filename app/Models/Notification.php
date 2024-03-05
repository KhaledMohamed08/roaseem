<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'is_read',
        'event',
        'user_id',
        'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
