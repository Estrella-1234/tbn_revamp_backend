<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Review::with('registration')->get();

        return view('reviews.index', compact('reviews'));
    }


    public function create(EventRegistration $registration)
    {
        return view('reviews.create', compact('registration'));
    }

    public function store(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'review' => 'required|string|max:255',
        ]);

        $registration->reviews()->create($request->all());

        return redirect()->route('reviews.index')->with('success', 'Review added successfully.');
    }

    public function edit(Review $review)
    {
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'review' => 'required|string|max:255',
        ]);

        $review->update($request->all());

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }

    public function addReview(Request $request, EventRegistration $registration): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $imagePath = null;

        // Handle the file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('review', $imageName, 'public');
        }

        // Create the review
        $review = $registration->reviews()->create([
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
            'image_path' => $imagePath, // save image path to the database
        ]);

        return response()->json(['message' => 'Review added successfully', 'review' => $review], 200);
    }


    public function updateReview(Request $request, Review $review): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $review->update([
            'review' => $request->input('review'),
        ]);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
    }

    public function deleteReview(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }

    public function getAllReviews(): JsonResponse
    {
        $reviews = Review::with('registration')->get();

        return response()->json(['reviews' => $reviews], 200);
    }

    public function getReviewbyId($id): JsonResponse
    {
        $review = Review::with('registration')->find($id);

        if (!$review) {
            return response()->json(['error' => 'Review not found'], 404);
        }

        return response()->json(['review' => $review], 200);
    }


}

