@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Event Registrations') }}</h1>

    <div class="d-flex align-items-center mb-3 ">
        <a href="{{ route('registrations.create') }}" class="btn btn-primary mr-3">Register for an Event</a>
        <a href="{{ route('registrations.export') }}" class="btn btn-secondary mr-auto">Export CSV</a>

        <form action="{{ route('registrations.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Registration Here..."
                       value="{{ request()->input('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>

    @if($registrations->count())
        <table class="table table-bordered table-striped ">
            <thead>
            <tr>
                <th>Event</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Affiliation</th>
                <th>Ticket Type</th>
{{--                <th>Notes</th>--}}
                <th>Status</th>
                <th>Attendance</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($registrations as $registration)
                <tr>
                    <td>{{ $registration->event->judul }}</td>
                    <td>{{ $registration->name }}</td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ $registration->phone }}</td>
                    <td>{{ $registration->affiliation }}</td>
                    <td>{{ $registration->ticket_type }}</td>
{{--                    <td>{{ $registration->notes }}</td>--}}
                    <td>{{ $registration->status }}</td>
                    <td>
                        @if($registration->attendance === 1)
                            <span class="text-success">Attended</span>
                        @elseif($registration->attendance === 0)
                            <span class="text-danger">Not Attended</span>
                        @else
                            <span class="text-warning">Unknown</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('registrations.show', $registration->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('registrations.edit', $registration->id) }}" class="btn btn-warning">Edit</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRegistrationModal-{{ $registration->id }}">
                            Delete
                        </button>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteRegistrationModal-{{ $registration->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="deleteRegistrationModalLabel-{{ $registration->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteRegistrationModalLabel-{{ $registration->id }}">Confirm Deletion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the registration for {{ $registration->name }} (ID: {{ $registration->id }})? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $registrations->links() }}
    @else
        <p>No registrations found.</p>
    @endif

@endsection


