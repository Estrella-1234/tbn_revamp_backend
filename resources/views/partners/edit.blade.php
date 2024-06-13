@extends('layouts.admin')

@section('main-content')
    <h1>Edit Partner</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $partner->name) }}" required>
        </div>

        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image">
            @if ($partner->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" width="100">
                </div>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Partner</button>
        <a href="{{ route('partners.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
