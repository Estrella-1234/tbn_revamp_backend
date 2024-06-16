<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Review::with('registration')->paginate(10);

        return view('reviews.index', compact('reviews'));
    }


    public function edit(Review $review)
    {
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'new_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Update the review text
        $review->review = $request->input('review');

        // Handle image update
        if ($request->hasFile('new_image')) {
            try {
                $image = $request->file('new_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // Convert and save as WebP format
                $webpImageName = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                $webpImagePath = 'review/' . $webpImageName; // Path for WebP image

                Image::make($image)
                    ->encode('webp', 80) // Convert to WebP with 80% quality
                    ->save(storage_path('app/public/' . $webpImagePath));

                // Delete the old image if it exists
                if ($review->image_path) {
                    Storage::disk('public')->delete($review->image_path);
                }

                // Update the review's image path
                $review->image_path = $webpImagePath;
            } catch (\Exception $e) {
                \Log::error('Image processing failed:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['image' => 'Image processing failed. Please try again.'])->withInput();
            }
        }

        // Save the updated review
        $review->save();

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        // Delete associated file if it exists
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }

        // Delete the review record
        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }

    //==================================================================================================================
    public function addReview(Request $request, EventRegistration $registration): JsonResponse
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|max:2048', // Adjusted validation rule to include JPEG and GIF
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Initialize image path variables
        $webpImagePath = null;

        // Handle the file upload if image is present in the request
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Generate unique image name

            // Convert and save as WebP format
            try {
                $webpImageName = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                $webpImagePath = 'review/' . $webpImageName; // Path for WebP image

                Image::make($image)
                    ->encode('webp', 80) // Convert to WebP with 80% quality
                    ->save(storage_path('app/public/' . $webpImagePath));

                // Delete the original image after successful conversion
                if (file_exists($image->getPathname())) {
                    unlink($image->getPathname());
                }
            } catch (\Exception $e) {
                // Handle conversion errors gracefully
                \Log::error('WebP conversion failed:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Image processing failed. Please try again.'], 500);
            }
        }

        // Create the review with validated data and saved image paths
        $review = $registration->reviews()->create([
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
            'image_path' => $webpImagePath, // Save WebP path if converted successfully
        ]);

        // Return success response with the created review data
        return response()->json(['message' => 'Review added successfully', 'review' => $review], 200);
    }



    public function updateReview(Request $request, Review $review): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'review' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $imagePath = $review->image_path; // Default to old image path

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Convert and save as WebP format
            try {
                $webpImageName = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                $webpImagePath = 'review/' . $webpImageName; // Path for WebP image

                Image::make($image)
                    ->encode('webp', 80) // Convert to WebP with 80% quality
                    ->save(storage_path('app/public/' . $webpImagePath));

                // Delete the old image if it exists
                if ($review->image_path) {
                    Storage::disk('public')->delete($review->image_path);
                }

                // Update image path to WebP
                $imagePath = $webpImagePath;
            } catch (\Exception $e) {
                // Handle conversion errors gracefully
                \Log::error('WebP conversion failed:', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Image processing failed. Please try again.'], 500);
            }
        }

        // Update the review
        $review->update([
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
            'image_path' => $imagePath,
        ]);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
    }

    public function deleteReview(Review $review): JsonResponse
    {
        // Delete associated file, if exists
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }

        // Delete the review record
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

