@extends('layouts.admin')

@section('main-content')
    <h1>Edit Review</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                @endforeach
                <li>{{ $error }}</li>
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea name="review" id="review" class="form-control" required>{{ old('review', $review->review) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Review</button>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
            </form>
        </div>
    </div>
@endsection
