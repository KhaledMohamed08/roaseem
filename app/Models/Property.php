<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'auction_id',
        'title',
        'desc',
        'region_id',
        'property_type_id',
        'address',
        'latitude',
        'longitude',
        'license_name',
        'license_end_date',
        'brokerage_contract_number',
        'license_number',
        'license_creation_date',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function city()
    {
        return $this->region->city;
    }

    public function country()
    {
        return $this->city->country;
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function type()
    {
        return $this->belongsTo(UnitType::class, 'property_type_id');
    }
}
