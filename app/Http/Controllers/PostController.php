<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10); // Paginate the results

        return view('posts.index', compact('posts'));
    }


    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content_type' => 'required|in:image,video',
            'image' => $request->content_type == 'image' ? 'required|image|mimes:jpeg,png,jpg,gif|max:5120' : '',
            'video_url' => $request->content_type == 'video' ? 'required|url' : '',
        ]);

        // Handle image upload if content type is image
        if ($request->content_type == 'image' && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        } else {
            $imagePath = null;
        }

        // Create a new Post instance
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->content = $request->content_type === 'image' ? $imagePath : $request->video_url;
        $post->content_type = $request->content_type;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    private function uploadImage($image)
    {
        $imageName = $image->store('posts', 'public'); // Store the image in storage/app/public/posts directory

        return Storage::url($imageName); // Return the URL of the uploaded image
    }


    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content_type' => 'required|in:image,video',
            'image' => $request->content_type == 'image' ? 'image|mimes:jpeg,png,jpg,gif|max:5120' : '',
            'video_url' => $request->content_type == 'video' ? 'required|url' : '',
        ]);

        // Handle image upload if content type is image
        if ($request->content_type == 'image' && $request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->content_type == 'image' && $post->content) {
                Storage::disk('public')->delete($post->content);
            }
            // Upload new image
            $imagePath = $request->file('image')->store('posts', 'public');
        } else {
            $imagePath = null;
        }

        $post->title = $request->title;
        $post->description = $request->description;
        $post->content = $request->content_type === 'image' ? $imagePath : $request->video_url;
        $post->content_type = $request->content_type;
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }


    public function destroy(Post $post)
    {
        // Delete the associated image file if the post has an image
        if ($post->content_type === 'image' && $post->content) {
            Storage::disk('public')->delete($post->content);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function getAllPost()
    {
        $posts = Post::all();
        return response()->json($posts);
    }
}
