<?php

namespace App\Http\Controllers\Pages;

use App\Models\User;
use App\Models\Car;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


use App\Models\CarReservation;
use App\Models\HotelReservation;

class CarController extends Controller
{
    public function addCar(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'model' => 'required|string',
            'registration_number' => 'required|string|unique:cars',
            'seats' => 'nullable|integer',
            'doors' => 'nullable|integer',
            'air_conditioning' => 'nullable|boolean',
            'transmission' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'fuel_fill_up' => 'nullable|string',
            'price_per_km' => 'nullable|numeric',
            'physical_disability_accessible' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check for validation failure
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName(); // Get the original name of the image
            $image->storeAs('public/cars', $imageName); // Store the image in the 'public/cars' directory with its original name
        }

        // Create a new car instance
        $car = new Car();
        $car->model = $request->model;
        $car->registration_number = $request->registration_number;
        $car->seats = $request->seats;
        $car->doors = $request->doors;
        $car->air_conditioning = $request->air_conditioning;
        $car->transmission = $request->transmission;
        $car->fuel_type = $request->fuel_type;
        $car->fuel_fill_up = $request->fuel_fill_up;
        $car->price_per_km = $request->price_per_km;
        $car->physical_disability_accessible = $request->physical_disability_accessible;
        $car->image = $imageName; // Save the image file name

        // Save the car
        $car->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Car added successfully',
            'data' => $car
        ], 201);
    }

    public function searchCar(Request $request)
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'back_to_same_location' => 'nullable|boolean',
                'location_of_receipt' => 'nullable|string',
                'location_of_delivery' => 'nullable|string',
                'date_of_receipt' => 'required|date_format:Y-m-d',
                'date_of_return' => 'required|date_format:Y-m-d',
                'need_driver' => 'nullable|boolean',
                'enable_physical_disability' => 'nullable|boolean',
                'car_id' => 'required|exists:cars,id', // Check if car ID exists in the cars table
            ]);

            // Check for validation failure
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Retrieve user details
            $userName = $user->name;
            $phoneNumber = $user->phone_number;

            // Check car availability based on provided dates
            $dateOfReceipt = $request->input('date_of_receipt');
            $dateOfReturn = $request->input('date_of_return');

            // Find the selected car
            $selectedCarId = $request->input('car_id');
            $car = Car::find($selectedCarId);

            // Check if the car exists
            if (!$car) {
                return response()->json([
                    'status' => false,
                    'message' => 'The selected car does not exist',
                ], 404);
            }

            // Check if the car is available for the specified dates
            $isAvailable = $car->reservations()->where(function ($query) use ($dateOfReceipt, $dateOfReturn) {
                $query->whereBetween('arrival_date', [$dateOfReceipt, $dateOfReturn])
                    ->orWhereBetween('return_date', [$dateOfReceipt, $dateOfReturn])
                    ->orWhere(function ($query) use ($dateOfReceipt, $dateOfReturn) {
                        $query->where('arrival_date', '<=', $dateOfReceipt)
                            ->where('return_date', '>=', $dateOfReturn);
                    });
            })->doesntExist();

            if (!$isAvailable) {
                return response()->json([
                    'status' => false,
                    'message' => 'The selected car is already reserved for the specified dates',
                ], 400);
            }

            // Save reservation
            $reservation = new CarReservation();
            $reservation->user_id = $user->id;
            $reservation->car_id = $selectedCarId;
            $reservation->name = $userName;
            $reservation->phone_number = $phoneNumber;
            $reservation->arrival_date = $dateOfReceipt;
            $reservation->return_date = $dateOfReturn;
            // Set other reservation properties as needed
            $reservation->save();

            return response()->json([
                'status' => true,
                'message' => 'Reservation saved successfully',
                'data' => $reservation,
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }



}
