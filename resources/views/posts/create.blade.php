@extends('layouts.admin')

@section('main-content')
        <h1>Create Post</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label for="content_type">Content Type</label>
                <select name="content_type" id="content_type" class="form-control">
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                </select>
            </div>
            <div id="image-upload" class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control-file">
            </div>
            <div id="video-url" class="form-group" style="display: none;">
                <label for="video_url">Video URL</label>
                <input type="text" name="video_url" id="video_url" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>


    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            removeButtons: 'Image,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Source,Code,Link,Unlink,Anchor,About,Subscript,Superscript,RemoveFormat',
        });

        document.getElementById('content_type').addEventListener('change', function() {
            var contentType = this.value;
            if (contentType === 'image') {
                document.getElementById('image-upload').style.display = 'block';
                document.getElementById('video-url').style.display = 'none';
            } else if (contentType === 'video') {
                document.getElementById('image-upload').style.display = 'none';
                document.getElementById('video-url').style.display = 'block';
            }
        });
    </script>
@endsection
