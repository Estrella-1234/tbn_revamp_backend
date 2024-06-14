<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $sections = ['Section1', 'Section2', 'Section3']; // Replace with your actual sections
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content_type' => 'required|in:image,video',
            'image' => $request->content_type == 'image' ? 'required|image|max:5120' : '',
            'video_url' => $request->content_type == 'video' ? 'required|url' : '',
        ]);

        if ($request->content_type == 'image' && $request->hasFile('image')) {
            // Load the image
            $image = Image::make($request->file('image'));

            // Compress and convert to WebP
            $webpPath = 'posts/' . uniqid() . '.webp';
            $image->encode('webp', 80)->save(storage_path('app/public/' . $webpPath));

            $imagePath = $webpPath;
        } else {
            $imagePath = null;
        }

        $post = new Post();
        $post->section = $request->section;
        $post->post_data = json_encode([
            'title' => $request->title,
            'description' => $request->description,
            'content_type' => $request->content_type,
            'content' => $request->content_type === 'image' ? $imagePath : $request->video_url,
        ]);
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
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
            'section' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content_type' => 'required|in:image,video',
            'image' => $request->content_type == 'image' ? 'image|max:5120' : '',
            'video_url' => $request->content_type == 'video' ? 'required|url' : '',
        ]);

        // Decode the post data from JSON to an array
        $postData = json_decode($post->post_data, true);

        // Check if content type is changed or a new file is uploaded
        $contentChanged = $request->content_type !== $postData['content_type'] || ($request->content_type == 'image' && $request->hasFile('image'));

        // Delete the old image or video file if content type is changed or a new file is uploaded
        if ($contentChanged && $postData['content_type'] === 'image' && $postData['content']) {
            Storage::disk('public')->delete($postData['content']);
        }

        // Handle image upload if content type is image
        if ($request->content_type == 'image' && $request->hasFile('image')) {
            // Load the image
            $image = Image::make($request->file('image'));

            // Compress and convert to WebP
            $webpPath = 'posts/' . uniqid() . '.webp';
            $image->encode('webp', 80)->save(storage_path('app/public/' . $webpPath));

            $imagePath = $webpPath;
        } else {
            $imagePath = $contentChanged ? null : $postData['content'];
        }

        // Update post data
        $postData = [
            'title' => $request->title,
            'description' => $request->description,
            'content_type' => $request->content_type,
            'content' => $request->content_type === 'image' ? $imagePath : $request->video_url,
        ];

        $post->section = $request->section;
        $post->post_data = json_encode($postData);
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }


    public function destroy(Post $post)
    {
        $postData = json_decode($post->post_data, true);

        if ($postData['content_type'] === 'image' && $postData['content']) {
            Storage::disk('public')->delete($postData['content']);
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }




    public function getAllPost()
    {
        $posts = Post::all();

        // Decode the post_data for each post and remove backslashes
        $decodedPosts = $posts->map(function ($post) {
            $postData = json_decode(stripslashes($post->post_data), true);
            return [
                'id' => $post->id,
                'section' => $post->section,
                'post_data' => [
                    'title' => $postData['title'] ?? null,
                    'description' => $postData['description'] ?? null,
                    'content_type' => $postData['content_type'] ?? null,
                    'content' => $postData['content'] ?? null,
                ],
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at
            ];
        });

        return response()->json($decodedPosts);
    }

}
