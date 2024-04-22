<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PlaceController extends Controller
{
    public function uploadPlaceData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:nature,seas,historical',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($request->hasFile('image')) {
            $imageName = $request->image->getClientOriginalName();
            $request->image->move(public_path('images/places'), $imageName);
        } else {
            return response()->json(['status' => false, 'message' => 'No image uploaded'], 400);
        }

        // Assuming validation is passed, create the place with all data
        $place = Place::create([
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'image' => $imageName, // Store just the image name or path
            'category' => $request->category
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Place created successfully',
            'data' => $place,
        ], 201);
    }
    public function getAllPlaces()
    {
        // Retrieve all places from the database
        $places = Place::all();

        return response()->json([
            'status' => true,
            'data' => $places,
        ]);
    }
    public function getPlaceById($id)
    {
        // Retrieve the place from the database based on the ID
        $place = Place::find($id);

        if (!$place) {
            return response()->json([
                'status' => false,
                'message' => 'Place not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $place,
        ]);
    }
    public function searchPlaces($name)
    {
        return Place::where('name', 'like', '%' . $name . '%')->get();
    }

    public function recommendPlaces()
    {
        $pythonScript = 'C:\Users\Mostafa\Desktop\RS_L3.py';
        $argument1 = 'Cairo';
        $argument2 = '1';

        $command = 'python3 ' . $pythonScript ;
        $output = exec($command);

        echo $output;
    }

}
