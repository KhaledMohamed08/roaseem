<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'comment',
        'rater_user_id',
        'rated_user_id',
    ];

    public function rater()
    {
       return $this->belongsTo(User::class,'rater_user_id');
    }

    public function rated()
    {
       return $this->belongsTo(User::class,'rated_user_id');
    }
}
