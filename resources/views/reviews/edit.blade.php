@extends('layouts.admin')

@section('main-content')
    <h1>Edit Review</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea name="review" id="review" class="form-control" required>{{ old('review', $review->review) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="image">Current Image:</label><br>
                    @if ($review->image_path)
                        <img src="{{ asset('storage/' . $review->image_path) }}" alt="Current Image" style="max-width: 300px;">
                    @else
                        <p>No image uploaded</p>
                    @endif
                </div>
                <div class="form-group">
                    <label for="new_image">Upload New Image:</label>
                    <input type="file" class="form-control-file" id="new_image" name="new_image">
                </div>
                <button type="submit" class="btn btn-primary">Update Review</button>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
            </form>
        </div>
    </div>
@endsection
