@extends('layouts.admin')

@section('main-content')
    <h1>Create User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                @endforeach
                <li>{{ $error }}</li>
            </ul>
        </div>
    @endif

    <form action="{{ $user->exists ? route('users.update', $user->id) : route('users.store') }}" method="POST">
        @csrf
        @if($user->exists)
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        @unless($user->exists)
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
        @endunless
        <div class="form-group">
            <label for="user_role">User Role</label>
            <select name="user_role" class="form-control" required>
                <option value="user" {{ old('user_role', $user->user_role) == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('user_role', $user->user_role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary mr-3">
                {{ $user->exists ? __('Update User') : __('Create User') }}
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </form>
@endsection
