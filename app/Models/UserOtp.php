<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;

    protected $table    =   'user_otps';

    protected $fillable =   ['phone', 'otp'];

    protected $hidden   =   ['updated_at', 'created_at'];
}
