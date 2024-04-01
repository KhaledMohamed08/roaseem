<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Auction extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'desc',
        'link',
        'start_date',
        'end_date',
        'start_time',
        'opening_price',
        'subscription_fee',
        'minimum_bid',
        'auctioneer_name',
        'id_number',
        'auction_license_number',
        'region_id',
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeOffline($query)
    {
        return $query->where('is_offline', true);
    }

    public function scopeOnline($query)
    {
        return $query->where('is_offline', false);
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
