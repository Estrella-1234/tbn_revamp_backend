@extends('layouts.admin')

@section('main-content')
        <h1>Create New Blog</h1>
        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="desc">Description</label>
                <textarea name="desc" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="d-flex align-items-center">
                <button type="submit" class="btn btn-primary mr-3">Create</button>
                <a href="{{ route('blogs.index') }}" class="btn btn-secondary ">Back to Blog</a>
            </div>
        </form>

        <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('desc');
        </script>
@endsection
