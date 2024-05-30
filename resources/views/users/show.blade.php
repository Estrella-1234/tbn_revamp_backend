@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('User Details') }}</h1>

    <div class="card">
        <div class="card-header">
            User Details
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Last Name:</strong> {{ $user->last_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Email Verified At:</strong> {{ $user->email_verified_at }}</p>
            <p><strong>Created At:</strong> {{ $user->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $user->updated_at }}</p>
        </div>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to Users</a>
@endsection
