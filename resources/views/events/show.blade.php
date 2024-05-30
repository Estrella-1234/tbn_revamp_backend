@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Event Details') }}</h1>

    <div class="card">
        <div class="card-header">
            Event Details
        </div>
        <div class="card-body">
            @if($event->poster_path)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $event->poster_path) }}" alt="Event Poster" style="max-width: 35%;;">
                </div>
            @endif
            <p><strong>Title:</strong> {{ $event->judul }}</p>
            <p><strong>Description:</strong> {{ $event->deskripsi }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->tanggal)->format('d M Y, H:i') }}</p>
            <p><strong>Location:</strong> {{ $event->lokasi }}</p>
            <p><strong>Speaker:</strong> {{ $event->pembicara }}</p>
            <p><strong>Price:</strong> {{ 'Rp ' . number_format($event->harga, 0, ',', '.') }}</p>
            <p><strong>Created At:</strong> {{ $event->created_at }}</p>
            <p><strong>Updated At:</strong> {{ $event->updated_at }}</p>
        </div>
    </div>

    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Back to Events</a>
@endsection
