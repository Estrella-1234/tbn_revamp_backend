@extends('layouts.admin')

@section('main-content')
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">{{ $blog->title }}</h1>
                <p class="text-muted">By: {{ $blog->user->name }}</p>
                <p class="card-text">{{ $blog->desc }}</p>
                @if ($blog->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $blog->image_path) }}" alt="Blog Image" class="img-fluid" style="max-width: 50%; height: auto;">
                    </div>
                @endif
                <div class="d-flex justify-content-start">
                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary mr-2">Edit</a>
                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <a href="{{ route('blogs.index') }}" class="ml-2 btn btn-secondary">Back to Blog</a>
                </div>
            </div>
        </div>

        <hr>
        <div class="card mt-4">
            <div class="card-body">
                <h2>Comments</h2>
                @foreach($blog->comments as $comment)
                    <div class="comment mb-3">
                        <p><strong>{{ $comment->user->name }}</strong> ({{ $comment->stars }} stars)</p>
                        <p>{{ $comment->comment }}</p>
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>

        <hr>
        <div class="card mt-4">
            <div class="card-body">
                <h3>Add a Comment</h3>
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
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>

@endsection
