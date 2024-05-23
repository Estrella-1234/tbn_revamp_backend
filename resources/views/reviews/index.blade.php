@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Manage Reviews') }}</h1>


        <div class="table-responsive">
            <a href="{{ route('registrations.index') }}" class="btn btn-primary mb-3">Back to Registrations</a>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Review</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>{{ $review->registration->name }}</td>
                        <td>{{ $review->review }}</td>
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
        </div>

@endsection
