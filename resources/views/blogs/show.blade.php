@extends('layouts.admin')

@section('main-content')
    <div class="container my-4">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">{{ $blog->title }}</h1>
                <p class="text-muted">By: {{ $blog->user->name }}</p>
                <p class="card-text">{{ $blog->desc }}</p>
                @if ($blog->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $blog->image_path) }}" alt="Blog Image" class="img-fluid" style="max-width: 100%; height: auto;">
                    </div>
                @endif
                <div class="d-flex justify-content-start">
                    <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-primary me-2 mr-2">Edit</a>
                    <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <a href="{{ route('blogs.index') }}" class="ml-2 btn btn-secondary ">Back to Blog</a>
                </div>
            </div>
        </div>
    </div>
@endsection
