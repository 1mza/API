<?php

namespace App\Http\Controllers\Pages;

use App\Models\Hotel;
use App\Models\HotelReservation;
use App\Models\Place;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    public function uploadHotelData(Request $request)
    {
        // Validation rules for hotel data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:hotels',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'rate' => 'required|numeric|min:0|max:10',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/hotels'), $imageName);
        } else {
            return response()->json(['status' => false, 'message' => 'No image uploaded'], 400);
        }

        // Create hotel record
        $hotel = Hotel::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'rate' => $request->rate,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Hotel created successfully',
            'data' => $hotel,
        ], 201);
    }

    public function getAllHotels()
    {
        return Hotel::filter()->get();
    }

    public function getHotelById($id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Hotel not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $hotel,
        ]);
    }

    public function searchHotels($name)
    {
        return Hotel::where('name', 'like', '%' . $name . '%')->get();
    }

    public function nearby($id)
    {
        $hotel = Hotel::find($id);

        // Check if the hotel exists
        if (!$hotel) {
            return response()->json(['status' => false, 'message' => 'Hotel not found'], 404);
        }

        $hotelLocation = $hotel->location;

        // Retrieve nearby places based on the location of the hotel
        $nearbyPlaces = Place::where('location', 'like', '%' . $hotelLocation . '%')->get();

        return response()->json(['status' => true, 'data' => $nearbyPlaces]);
    }

    public function reserveHotel(Request $request, $hotelId)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'arrive_date' => 'required|date',
            'leave_date' => 'required|date',
            'num_of_adults' => 'required|integer|min:1',
            'num_of_children' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Check if the hotel exists
        $hotel = Hotel::find($hotelId);
        if (!$hotel) {
            return response()->json([
                'status' => false,
                'message' => 'Hotel not found',
            ], 404);
        }

        // Create a new reservation for the hotel
        $reservation = new HotelReservation();
        $reservation->hotel_id = $hotelId;
        $reservation->name = $request->input('name');
        $reservation->phone_number = $request->input('phone_number');
        $reservation->arrive_date = $request->input('arrive_date');
        $reservation->leave_date = $request->input('leave_date');
        $reservation->num_of_adults = $request->input('num_of_adults');
        $reservation->num_of_children = $request->input('num_of_children');

        // Save the reservation
        if (!$reservation->save()) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create reservation',
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Reservation created successfully',
            'data' => $reservation,
        ], 201);
    }
}
