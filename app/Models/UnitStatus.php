<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function unitReq()
    {
        return $this->hasOne(UnitReq::class);
    }
}
