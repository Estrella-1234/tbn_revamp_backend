@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Registration') }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('registrations.update', $registration->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="event_id">Event:</label>
                    <select name="event_id" id="event_id" class="form-control">
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ $registration->event_id == $event->id ? 'selected' : '' }}>{{ $event->judul }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $registration->name) }}">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $registration->email) }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $registration->phone) }}">
                </div>
                <div class="form-group">
                    <label for="affiliation">Affiliation:</label>
                    <input type="text" name="affiliation" id="affiliation" class="form-control" value="{{ old('affiliation', $registration->affiliation) }}">
                </div>
                <div class="form-group">
                    <label for="ticket_type">Ticket Type:</label>
                    <input type="text" name="ticket_type" id="ticket_type" class="form-control" value="{{ old('ticket_type', $registration->ticket_type) }}">
                </div>
                <div class="form-group">
                    <label for="notes">Notes:</label>
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes', $registration->notes) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="Pending" {{ $registration->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Accepted" {{ $registration->status === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="Rejected" {{ $registration->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-3">Update Registration</button>
                    <a href="{{ route('registrations.index') }}" class="btn btn-secondary ">Back to Events</a>
                </div>
            </form>
        </div>
    </div>
@endsection
