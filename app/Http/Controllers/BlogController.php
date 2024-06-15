<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')->paginate(10);
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|image|max:2048', // Allow only image files up to 2MB
        ]);

        try {
            // Check if there is an uploaded image
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Create an instance of Intervention Image
                $imageWebp = \Image::make($image);

                // Generate a unique filename for the webp image
                $webpPath = 'Blog/' . uniqid() . '.webp';

                // Save the image as WebP format with 80% quality
                $imageWebp->encode('webp', 80)->save(storage_path('app/public/' . $webpPath));

                // Store the WebP image path in $imagePath
                $imagePath = $webpPath;
            } else {
                $imagePath = null;
            }

            // Create the blog entry
            $blog = Blog::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'desc' => $request->desc,
                'image_path' => $imagePath,
            ]);

            return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) { // Unique constraint violation
                return redirect()->back()->withErrors(['title' => 'A blog with the same title already exists.'])->withInput();
            }
            // Handle other database-related errors if needed
            return redirect()->back()->withErrors(['error' => 'An error occurred. Please try again.'])->withInput();
        }
    }



    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return view('blogs.show', compact('blog'));
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|image|max:2048', // Allow only image files up to 2MB
        ]);

        $blog = Blog::findOrFail($id);

        try {
            // Check if the updated title already exists in other blogs
            if ($request->title !== $blog->title && Blog::where('title', $request->title)->exists()) {
                throw new \Exception('A blog with the same title already exists.');
            }

            // Handle image upload and processing
            if ($request->hasFile('image')) {
                try {
                    $image = $request->file('image');
                    $webpFileName = uniqid() . '.webp';
                    $webpPath = 'public/Blog/' . $webpFileName; // Adjusted path to 'blog'
                    $images_path =  'Blog/' . $webpFileName;
                    // Process and save the uploaded image as WebP using Intervention Image
                    Image::make($image)
                        ->encode('webp', 80)
                        ->save(storage_path('app/' . $webpPath));

                    // If the new image is processed successfully, delete the old image
                    if ($blog->image_path && Storage::exists('public/' . $blog->image_path)) {
                        Storage::delete('public/' . $blog->image_path);
                    }

                    // Update the blog's image path
                    $blog->image_path = $images_path;
                } catch (\Exception $e) {
                    Log::error('Image processing failed:', ['error' => $e->getMessage()]);
                    return redirect()->back()->withErrors(['image' => 'Image processing failed. Please try again.'])->withInput();
                }
            }

            // Update the blog
            $blog->title = $request->title;
            $blog->desc = $request->desc;
            $blog->save();

            return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
        } catch (\Exception $e) {
            Log::error('Blog update failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['title' => $e->getMessage()])->withInput();
        }
    }



    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image_path) {
            \Storage::delete('public/' . $blog->image_path);
        }
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully.');
    }

    //==================================================================================================================
    // Get all blogs
    public function getAll()
    {
        $blogs = Blog::with('user')->get();
        if ($blogs->count() == 0) {
            return response()->json(['message' => 'No blogs found'], 404);
        } else {
            return response()->json($blogs, 200);
        }
    }

    // Get a blog by ID
    public function getById($id)
    {
        $blog = Blog::with('user')->find($id);
        if ($blog) {
            return response()->json($blog);
        } else {
            return response()->json(['message' => 'Blog not found'], 404);
        }
    }

    public function getBySlug($slug)
    {
        $blog = Blog::where('slug', $slug)->with(['user','comments.user'])->first();
        if ($blog) {
            return response()->json($blog);
        } else {
            return response()->json(['message' => 'Blog not found'], 404);
        }
    }


}
