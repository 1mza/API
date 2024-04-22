<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'name',
        'phone_number',
        'arrive_date',
        'leave_date',
        'num_of_adults',
        'num_of_children',
        // Add any other relevant fields
    ];

    // Define relationships if needed
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
