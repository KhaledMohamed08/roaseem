<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'max_price',
        'max_user',
    ];
}
