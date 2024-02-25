<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Unit extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'region_id',
        'address',
        'latitude',
        'longitude',
        'ad_title',
        'unit_type',
        'contract_type',
        'purpos',
        'interface',
        'floor_number',
        'area',
        'street_width',
        'payment_method',
        'price',
        'descreption',
        'services',
        'bedrooms',
        'living_rooms',
        'bathrooms',
        'kitchens',
        'licensor_name',
        'advertising_license_number',
        'brokerage_documentation_license_number',
        'rights_and_obligations',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id')->withTimestamps();
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
}
