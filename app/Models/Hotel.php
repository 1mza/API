<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'name',
        'image',
        'location',
        'description',
        'rate',
        'wifi',
        'pool',
        'car_parking',
        'sustainable_travel_level',
        'disability_accommodation'
    ];
}

