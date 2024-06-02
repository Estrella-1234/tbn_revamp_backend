<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content_type' => 'required|in:image,video',
            'image' => $request->content_type == 'image' ? 'required|image|mimes:jpeg,png,jpg,gif|max:5120' : '',
            'video_url' => $request->content_type == 'video' ? 'required|url' : '',
        ]);

        if ($request->content_type == 'image' && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
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
            'image' => $request->content_type == 'image' ? 'image|mimes:jpeg,png,jpg,gif|max:5120' : '',
            'video_url' => $request->content_type == 'video' ? 'required|url' : '',
        ]);

        if ($request->content_type == 'image' && $request->hasFile('image')) {
            if ($post->content_type == 'image' && $post->content) {
                Storage::disk('public')->delete($post->content);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
        } else {
            $imagePath = null;
        }

        $post->section = $request->section;
        $post->post_data = json_encode([
            'title' => $request->title,
            'description' => $request->description,
            'content_type' => $request->content_type,
            'content' => $request->content_type === 'image' ? $imagePath : $request->video_url,
        ]);
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
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
