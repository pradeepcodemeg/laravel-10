<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    // type', ['All', 'Group of User', 'Single User']

    protected $table        =   'promo_codes';

    protected $fillable     =   ['type', 'user_id', 'code', 'discount_percent', 'title', 'description'];

    protected $hidden       =   ['created_at', 'updated_at'];
}
