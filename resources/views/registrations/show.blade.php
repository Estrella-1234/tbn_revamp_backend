@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('View Registration') }}</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">User Information</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id">User Account:</label>
                        <p class="form-control-static">{{ $registration->user->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_email">User Email:</label>
                        <p class="form-control-static">{{ $registration->user->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="event_id">Event:</label>
                        <p class="form-control-static">{{ $registration->event->judul }}</p>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <p class="form-control-static">{{ $registration->name }}</p>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <p class="form-control-static">{{ $registration->email }}</p>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <p class="form-control-static">{{ $registration->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label for="affiliation">Affiliation:</label>
                        <p class="form-control-static">{{ $registration->affiliation }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ticket_type">Ticket Type:</label>
                        <p class="form-control-static">{{ $registration->ticket_type }}</p>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <p class="form-control-static">{{ $registration->notes }}</p>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <p class="form-control-static">{{ $registration->status }}</p>
                    </div>
                    <div class="form-group">
                        <label for="attendance">Attendance:</label>
                        <p class="form-control-static">
                            @if($registration->attendance === 1)
                                <span class="text-success">Attended</span>
                            @elseif($registration->attendance === 0)
                                <span class="text-danger">Not Attended</span>
                            @else
                                <span class="text-warning">Unknown</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('registrations.index') }}" class="btn btn-secondary">Back to Registrations</a>
            </div>
        </div>
    </div>
@endsection
