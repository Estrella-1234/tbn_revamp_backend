@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Events') }}</h1>


    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('events.create') }}" class="btn btn-primary mr-auto">Add Event</a>

        <form action="{{ route('events.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Event Here..."
                       value="{{ request()->input('search') }}">
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
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Speaker</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($events as $event)
            <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->judul }}</td>
                <td>{{ $event->deskripsi }}</td>
                <td>{{ $event->tanggal }}</td>
                <td>{{ $event->pembicara }}</td>
                <td>
                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
                    <button type="button" class="btn btn-danger delete-event" data-event-id="{{ $event->id }}"
                            data-toggle="modal" data-target="#deleteEventModal-{{ $event->id }}">Delete
                    </button>
                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-info">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center">
        {{ $events->links() }}
    </div>

    @foreach($events as $event)
        <div class="modal fade" id="deleteEventModal-{{ $event->id }}" data-backdrop="static" data-keyboard="false"
             tabindex="-1" aria-labelledby="deleteEventModalLabel-{{ $event->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEventModalLabel-{{ $event->id }}">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete event "{{ $event->judul }}" (ID: {{ $event->id }})? This action
                        cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                              style="display:inline-block;">
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
