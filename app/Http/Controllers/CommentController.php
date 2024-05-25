<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($blogId)
    {
        $blog = Blog::with('comments.user')->findOrFail($blogId);
        return view('comments.index', compact('blog'));
    }

    public function store(Request $request, $blogId)
    {
        $request->validate([
            'comment' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
        ]);

        Comment::create([
            'blog_id' => $blogId,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'stars' => $request->stars,
        ]);

        return redirect()->route('comments.index', $blogId)->with('success', 'Comment added successfully.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}

