<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp',
        'password',
        // 'otp',
        // 'is_verified',
        'role',
        'tax_number',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function unites()
    {
        return $this->hasMany(Unit::class);
    }

    // public static function companies()
    // {
    //     return User::where('role', 'company')->get();
    // }

    // Method to get users with the role 'user'
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    // Method to get users with the role 'company'
    public function scopeCompanies($query)
    {
        return $query->where('role', 'company');
    }

    public function favorites()
    {
        return $this->belongsToMany(Unit::class, 'favorites', 'user_id', 'unit_id')->withTimestamps();
    }

    public function unitReqUser()
    {
        return $this->hasMany(UnitReqUser::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function unitView()
    {
        return $this->hasMany(UnitViews::class);
    }
}
