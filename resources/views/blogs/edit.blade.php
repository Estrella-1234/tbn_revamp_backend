@extends('layouts.admin')

@section('main-content')
        <h1>Edit Blog</h1>
        <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" class="form-control" required>{{ $blog->desc }}</textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control">
                @if ($blog->image_path)
                    <img src="{{ asset('storage/' . $blog->image_path) }}" alt="Blog Image" style="max-width: 35%" class="mt-3">
                @endif
            </div>

            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-3">Update</button>
                <a href="{{ route('blogs.index') }}" class="btn btn-secondary ">Back to Blog</a>
            </div>
        </form>

        <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('desc');
        </script>
@endsection
