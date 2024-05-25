@extends('layouts.admin')

@section('main-content')
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        <p>{{ $blog->desc }}</p>

        <hr>

        <h2>Comments</h2>

        @foreach($blog->comments as $comment)
            <div class="comment">
                <p><strong>{{ $comment->user->name }}</strong> ({{ $comment->stars }} stars)</p>
                <p>{{ $comment->comment }}</p>
                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            <hr>
        @endforeach

        <h2>Add a Comment</h2>
        <form action="{{ route('comments.store', $blog->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea name="comment" id="comment" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="stars">Stars</label>
                <select name="stars" id="stars" class="form-control" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
