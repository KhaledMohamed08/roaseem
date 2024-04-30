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
        'user_id',
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

    public function subscibers()
    {
        return $this->hasMany(Subscriper::class);
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasOne(AuctionDetails::class);
    }
    
    public function AuctionUser()
    {
        return $this->hasMany(AuctionUser::class);
    }
}
