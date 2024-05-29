<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 'delivery_type', ['Hand to Hand', 'shipping Company']

    protected $fillable = ['platform_id', 'category_id', 'name', 'price', 'quantity', 'cover_photo', 'images', 'location', 'description', 'delivery_type'];
}
