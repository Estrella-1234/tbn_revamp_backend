@extends('layouts.admin')

@section('main-content')
    <h1> Edit Event</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                @endforeach
                <li>{{ $error }}</li>
            </ul>
        </div>
    @endif

    <form action="{{ $event->exists ? route('events.update', $event->id) : route('events.store') }}" method="POST"
          enctype="multipart/form-data">
        @csrf
        @if($event->exists)
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="judul">Title</label>
            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $event->judul) }}"
                   required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Description</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                      required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
        </div>
        <div class="form-group">
            <label for="tanggal">Date</label>
            <input type="datetime-local" class="form-control" id="tanggal" name="tanggal"
                   value="{{ old('tanggal', \Carbon\Carbon::parse($event->tanggal)->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div class="form-group">
            <label for="lokasi">Location</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi"
                   value="{{ old('lokasi', $event->lokasi) }}" required>
        </div>
        <div class="form-group">
            <label for="pembicara">Speaker</label>
            <input type="text" class="form-control" id="pembicara" name="pembicara"
                   value="{{ old('pembicara', $event->pembicara) }}" required>
        </div>
        <div class="form-group">
            <label for="harga">Price</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga', $event->harga) }}"
                   required>
        </div>
        <div class="form-group">
            <label for="poster_path">Poster</label>
            <input type="file" class="form-control-file" id="poster_path" name="poster_path" accept="image/*">
        </div>
        @if($event->poster_path)
            <div class="form-group">
                <label>Current Poster</label><br>
                <img src="{{ asset('storage/'.$event->poster_path) }}" alt="Current Poster" style="max-width: 35%;">
            </div>
        @endif
        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-primary mr-3">
                {{ $event->exists ? __('Update Event') : __('Create Event') }}
            </button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary ">Back to Events</a>
        </div>
    </form>

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('deskripsi');
    </script>
@endsection
