@extends('layouts.admin')

@section('main-content')
    <h1>Register for an Event</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                @endforeach
                <li>{{ $error }}</li>
            </ul>
        </div>
    @endif

    <form action="{{ route('registrations.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="event_id">Event</label>
            <select name="event_id" id="event_id" class="form-control">
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->judul }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="affiliation">Affiliation</label>
            <input type="text" name="affiliation" id="affiliation" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="ticket_type">Ticket Type</label>
            <input type="text" name="ticket_type" id="ticket_type" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('registrations.index') }}" class="btn btn-secondary ">Back to Registrations</a>
    </form>
@endsection
