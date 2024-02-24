<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitReq extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'phone',
        'email',
        'unit_types',
        'status',
        'purpose',
        'area',
        'price',
        'description',
        'ad_period',
        'entity_type',
        'city_id',
        'user_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function unitReqUser()
    {
        return $this->hasMany(UnitReqUser::class);
    }
}
