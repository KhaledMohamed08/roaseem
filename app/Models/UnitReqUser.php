<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitReqUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "unit_req_id",
        "user_id"
    ];

    public function unitReq()
    {
        return $this->belongsTo(UnitReq::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
