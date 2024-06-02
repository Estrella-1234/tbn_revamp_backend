@extends('layouts.admin')

@section('main-content')
    <div class="container">
        <h1>Edit Post</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $postData = json_decode(stripslashes($post->post_data), true);
        @endphp

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="section">Section</label>
                <input type="text" name="section" id="section" class="form-control" value="{{ $post->section }}">
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $postData['title'] ?? '' }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ $postData['description'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label for="content_type">Content Type</label>
                <select name="content_type" id="content_type" class="form-control">
                    <option value="image" {{ isset($postData['content_type']) && $postData['content_type'] === 'image' ? 'selected' : '' }}>Image</option>
                    <option value="video" {{ isset($postData['content_type']) && $postData['content_type'] === 'video' ? 'selected' : '' }}>Video</option>
                </select>
            </div>
            <div id="image-upload" class="form-group {{ isset($postData['content_type']) && $postData['content_type'] !== 'image' ? 'd-none' : '' }}">
                <label for="image">Image</label><br>
                @if(isset($postData['content_type']) && $postData['content_type'] === 'image' && isset($postData['content']))
                    <img src="{{ asset('storage/' . $postData['content']) }}" alt="Old Image" style="max-width: 35%;">
                @endif
                <input type="file" name="image" id="image" class="form-control-file">
            </div>
            <div id="video-url" class="form-group {{ isset($postData['content_type']) && $postData['content_type'] !== 'video' ? 'd-none' : '' }}">
                <label for="video_url">Video URL</label>
                <input type="text" name="video_url" id="video_url" class="form-control" value="{{ isset($postData['content_type']) && $postData['content_type'] === 'video' ? $postData['content'] : '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', {
            removeButtons: 'Image,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Source,Code,Link,Unlink,Anchor,About,Subscript,Superscript,RemoveFormat',
        });

        document.addEventListener("DOMContentLoaded", function () {
            var contentSelect = document.getElementById('content_type');
            var imageUpload = document.getElementById('image-upload');
            var videoUrl = document.getElementById('video-url');

            function toggleContentFields() {
                if (contentSelect.value === 'image') {
                    imageUpload.classList.remove('d-none');
                    videoUrl.classList.add('d-none');
                } else if (contentSelect.value === 'video') {
                    videoUrl.classList.remove('d-none');
                    imageUpload.classList.add('d-none');
                }
            }

            contentSelect.addEventListener('change', toggleContentFields);

            // Trigger change event on page load
            toggleContentFields();
        });
    </script>
@endsection
