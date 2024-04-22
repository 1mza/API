<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'name',
        'phone_number',
        'arrival_date',
        'return_date',
        'need_driver',
        'physical_disability_accessible',
        // Add any other relevant fields
    ];

    // Define relationship with Car model
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function reservations()
{
    return $this->hasMany(CarReservation::class);
}
}
