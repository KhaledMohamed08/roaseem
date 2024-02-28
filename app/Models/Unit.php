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
        'unit_type_id',
        'unit_status_id',
        'unit_purpose_id',
        'unit_interface_id',
        'created_year',
        'floor_number',
        'area',
        'street_width',
        'unit_payment_id',
        'price',
        'descreption',
        // 'services',
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

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function status()
    {
        return $this->belongsTo(UnitStatus::class, 'unit_status_id');
    }

    public function purpose()
    {
        return $this->belongsTo(UnitPurpose::class, 'unit_purpose_id');
    }

    public function interface()
    {
        return $this->belongsTo(UnitInterface::class, 'unit_interface_id');
    }

    public function payment()
    {
        return $this->belongsTo(UnitPayment::class, 'unit_payment_id');
    }

    
    public function unitView()
    {
        $this->hasMany(UnitViews::class);
    }
}
