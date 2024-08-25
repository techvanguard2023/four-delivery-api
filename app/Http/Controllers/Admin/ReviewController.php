<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return Review::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'required|exists:customers,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review = Review::create($validatedData);

        return response()->json($review, 201);
    }

    public function show(Review $review)
    {
        return $review;
    }

    public function update(Request $request, Review $review)
    {
        $validatedData = $request->validate([
            'order_id' => 'sometimes|exists:orders,id',
            'customer_id' => 'sometimes|exists:customers,id',
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review->update($validatedData);

        return response()->json($review, 200);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(null, 204);
    }
}
