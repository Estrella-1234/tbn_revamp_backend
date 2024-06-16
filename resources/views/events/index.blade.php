@extends('layouts.admin')

@section('main-content')
    <h1>Events</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('events.create') }}" class="btn btn-primary mr-auto">Add Event</a>
    </div>



    <table id="eventsTable" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>No</th>
            <th>Poster</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Location</th>
            <th>Speaker</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-center align-middle">
                    @if($event->poster_path)
                        <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Event Poster" style="max-width: 250px;">
                    @endif
                </td>
                <td>{{ $event->judul }}</td>
                <td>{!! $event->deskripsi !!}</td>
                <td>{{ $event->tanggal }}</td>
                <td>{{ $event->lokasi }}</td>
                <td>{{ $event->pembicara }}</td>
                <td>{{ 'Rp ' . number_format($event->harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
                    <button type="button" class="btn btn-danger delete-event" data-event-id="{{ $event->id }}"
                            data-toggle="modal" data-target="#deleteEventModal-{{ $event->id }}">Delete
                    </button>
                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-info">View</a> <!-- Update this line to use slug -->
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>


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

    <script>
        $(document).ready(function() {
            $('#eventsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100]
            });
        });
    </script>
@endsection
