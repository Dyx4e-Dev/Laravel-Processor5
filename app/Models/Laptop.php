<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    protected $fillable = [
        'name',
        'photo',
        'brand',
        'processor',
        'ram',
        'storage',
        'vga',
        'screen_size',
        'price',
        'recommendation',
        'app_usage',
    ];

    protected $casts = [
        'recommendation' => 'array',
        'price' => 'decimal:2',
    ];
}
