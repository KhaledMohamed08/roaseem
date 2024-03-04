<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $table = 'sevices';

    protected $fillable = [
        'service_id',
        'unit_id',
    ];

    public function unites()
    {
        return $this->belongsToMany(Unit::class);
    }

    public function unitServices()
    {
        return $this->belongsToMany(UnitService::class);
    }
}
