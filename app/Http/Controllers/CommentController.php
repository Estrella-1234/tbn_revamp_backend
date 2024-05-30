<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    //==================================================================================================================
    public function getallComments($blogId)
    {
        $comments = Comment::with('user')->where('blog_id', $blogId)->get();
        if ($comments) {
            return response()->json($comments);
        } else {
            return response()->json(['message' => 'No comments found'], 404);
        }
    }

    public function getComment($id)
    {
        $comment = Comment::with('user')->findOrFail($id);
        if ($comment) {
            return response()->json($comment);
        } else {
            return response()->json(['message' => 'Comment not found'], 404);
        }
    }
    public function getUserComment($id)
    {
        $comment = Comment::with('user')->where('user_id', $id)->get();
        if ($comment) {
            return response()->json($comment);
        } else {
            return response()->json(['message' => 'No comments found'], 404);
        }
    }

    public function createComment(Request $request, $blogId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $comment = Comment::create([
            'blog_id' => $blogId,
            'user_id' => $request->user_id,
            'comment' => $request->comment,
            'stars' => $request->stars,
        ]);

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
    }

    public function editComment(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $comment = Comment::findOrFail($id);
        $comment->update([
            'comment' => $request->comment,
            'stars' => $request->stars,
        ]);

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
    }

    public function deleteComment()
    {

    }
}

