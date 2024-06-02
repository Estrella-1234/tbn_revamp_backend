@extends('layouts.admin')

@section('main-content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p><strong>Description:</strong> {{ $post->description }}</p>
        <p><strong>Content:</strong> {{ $post->content }}</p>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to Posts</a>
    </div>
@endsection
