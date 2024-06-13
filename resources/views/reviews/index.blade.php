@extends('layouts.admin')

@section('main-content')
    <h1>Manage Reviews</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-responsive">
        <a href="{{ route('registrations.index') }}" class="btn btn-primary mb-3">Back to Registrations</a>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>No</th>
                <th>Event</th>
                <th>User</th>
                <th>Image</th>
                <th>Review</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reviews as $index => $review)
                <tr>
                    <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $index + 1 }}</td>
                    <td>{{ $review->registration->event->judul }}</td>
                    <td>{{ $review->registration->name }}</td>
                    <td class="text-center align-middle">
                        @if($review->image_path)
                            <img src="{{ asset('storage/' . $review->image_path) }}" alt="Review Poster" style="max-width: 250px;">
                        @endif
                    </td>
                    <td>{{ $review->review }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>
                        <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        <div class="d-flex justify-content-center">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
