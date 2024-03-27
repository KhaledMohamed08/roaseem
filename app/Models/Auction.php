<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $filable = [
        'title',
        'desc',
        'link',
        'start_date',
        'end_data',
        'start_time',
        'opening_price',
        'subscription_fee',
        'minimum_bid',
        'auctioneer_name',
        'id_number',
        'auction_license_number',
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
}
