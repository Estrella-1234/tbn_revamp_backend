@extends('layouts.admin')

@section('main-content')
    <h1>Add Review</h1>

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
            <form action="{{ route('reviews.store', $registration->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea name="review" id="review" class="form-control" required>{{ old('review') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Review</button>
                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">Back to Reviews</a>
            </form>
        </div>
    </div>
@endsection
