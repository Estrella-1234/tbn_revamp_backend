@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Users') }}</h1>

    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-primary mr-auto">Add User</a>

        <form action="{{ route('users.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search User Here..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>



    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                    <button type="button" class="btn btn-danger delete-user" data-user-id="{{ $user->id }}" data-toggle="modal" data-target="#deleteUserModal-{{ $user->id }}">Delete</button>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>

    @foreach($users as $user)
        <div class="modal fade" id="deleteUserModal-{{ $user->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="deleteUserModalLabel-{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel-{{ $user->id }}">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete user {{ $user->name }} (ID: {{ $user->id }})? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
