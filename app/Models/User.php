<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 'role', ['Admin', 'Trader', 'User']

    protected $fillable = ['name', 'expires_at', 'email', 'password', 'role', 'phone', 'image', 'city', 'store_name', 'device_token', 'status', 'region', 'street', 'building_no', 'address', 'postal_code', 'district', 'latitude', 'longitude'];

    protected $hidden = [
        'password',
        'updated_at',
        'created_at',
        'remember_token',
    ];

    public function getImageAttribute($value)
    {
        if ($value) {
            return asset('uploads/user/' . $value);
        }

        $defaultImg = config('constants.NO_USER_IMG');
        return asset($defaultImg);
    }
}
