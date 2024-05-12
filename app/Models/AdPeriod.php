<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPeriod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'days_num',
    ];

    public function unitReq()
    {
        return $this->hasMany(UnitReq::class);
    }

}
