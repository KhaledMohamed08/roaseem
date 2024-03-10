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
        'unit_types_id',
        'unit_status_id',
        'unit_purpose_id',
        'max_area',
        'min_area',
        'max_price',
        'min_price',
        'description',
        'bed_rooms',
        'bath_rooms',
        'ad_period',
        'entity_type',
        'city_id',
        'user_id',
    ];

    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    public function unitStatus()
    {
        return $this->belongsTo(UnitStatus::class);
    }

    public function unitPurpose()
    {
        return $this->belongsTo(UnitPurpose::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function unitReqUser()
    {
        return $this->hasMany(UnitReqUser::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
