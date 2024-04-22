<?php

namespace App\Http\Controllers\Pages;

use App\Models\Entertainment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EntertainmentController extends Controller
{
    public function uploadEntertainmentData(Request $request)
    {
        // Validation rules for entertainment data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:entertainments',
            'category' => 'required|in:مأكولات بحريه,مشويات و كشري,سوبرماركت',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'rate' => 'required|numeric|min:0|max:10',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'physical_disability_accessible' => 'required|boolean',
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
            $request->image->move(public_path('images/entertainments'), $imageName);
        } else {
            return response()->json(['status' => false, 'message' => 'No image uploaded'], 400);
        }

        // Create entertainment record
        $entertainment = Entertainment::create([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'rate' => $request->rate,
            'image' => $imageName,
            'physical_disability_accessible' => $request->physical_disability_accessible,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Entertainment venue created successfully',
            'data' => $entertainment,
        ], 201);
    }

    

    public function getEntertainmentById($id)
    {
        $entertainment = Entertainment::find($id);

        if (!$entertainment) {
            return response()->json([
                'status' => false,
                'message' => 'Entertainment venue not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $entertainment,
        ]);
    }

    public function searchEntertainments($name)
    {
        return Entertainment::where('name', 'like', '%' . $name . '%')->get();
    }

    public function getByCategory($category)
    {
        // Retrieve entertainment venues by category
        $entertainment = Entertainment::where('category', $category)->get();

        return response()->json([
            'status' => true,
            'data' => $entertainment,
        ]);
    }
}
