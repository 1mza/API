<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entertainment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'location',
        'description',
        'rate',
        'physical_disability_accessible',
        'image',
    ];
}
