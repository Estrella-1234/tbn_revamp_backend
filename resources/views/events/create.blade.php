@extends('layouts.admin')

@section('main-content')
    <h1 class="h3 mb-4 text-gray-800">{{ __('Create Event') }}</h1>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="judul">Title</label>
            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul') }}" required>
        </div>
        <div class="form-group">
            <label for="deskripsi">Description</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
        </div>
        <div class="form-group">
            <label for="tanggal">Date</label>
            <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
        </div>
        <div class="form-group">
            <label for="lokasi">Location</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required>
        </div>
        <div class="form-group">
            <label for="pembicara">Speaker</label>
            <input type="text" class="form-control" id="pembicara" name="pembicara" value="{{ old('pembicara') }}" required>
        </div>
        <div class="form-group">
            <label for="harga">Price</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga') }}" required>
            <div class="form-group">
                <label for="poster_path">Poster</label>
                <input type="file" class="form-control-file" id="poster_path" name="poster_path" accept="image/*" required>
            </div>
            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-3">{{ __('Create Event') }}</button>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">{{ __('Back to Events') }}</a>
            </div>
        </div>
    </form>

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('deskripsi');
    </script>

@endsection
