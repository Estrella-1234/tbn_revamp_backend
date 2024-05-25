<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('Blog', 'public') : null;

        Blog::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'desc' => $request->desc,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
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
            'image' => 'nullable|image|max:2048',
        ]);

        $blog = Blog::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($blog->image_path) {
                \Storage::delete('public/' . $blog->image_path);
            }
            $imagePath = $request->file('image')->store('Blog', 'public');
            $blog->image_path = $imagePath;
        }

        $blog->title = $request->title;
        $blog->desc = $request->desc;
        $blog->save();

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
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

    // Create a new blog
    public function createF(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $imagePath = null;

        // Handle the file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('Blog', $imageName, 'public');
        }

        $blog = Blog::create([
            'title' => $request->title,
            'desc' => $request->desc,
            'image_path' => $imagePath,
            'user_id' => $request->user_id,
        ]);
//        dd($imagePath);
        return response()->json($blog, 201);
    }

    // Update a blog
    public function updateF(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'desc' => 'sometimes|required|string',
            'image_path' => 'nullable|string',
            'user_id' => 'sometimes|required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $blog = Blog::find($id);
        if ($blog) {
            $blog->update($request->all());
            return response()->json($blog);
        } else {
            return response()->json(['message' => 'Blog not found'], 404);
        }
    }

    // Delete a blog
    public function deleteF($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            $blog->delete();
            return response()->json(['message' => 'Blog deleted successfully']);
        } else {
            return response()->json(['message' => 'Blog not found'], 404);
        }
    }
}
