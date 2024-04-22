<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['model', 'registration_number', 'description', 'seats', 'doors', 'air_conditioning', 'transmission', 'fuel_type', 'fuel_fill_up', 'price_per_km', 'min_rental_days', 'collision_damage_waiver', 'theft_protection', 'physical_disability_accessible', 'image'];
    public function reservations()
{
    return $this->hasMany(CarReservation::class);
}
}
