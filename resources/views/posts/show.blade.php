@extends('layouts.admin')

@section('main-content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>Description: {!! $post->description !!}</p>

        @if ($post->content_type === 'image')
            <img src="{{ asset('storage/' . $post->content) }}" alt="Post Image" style="max-width: 35%;;">
        @elseif ($post->content_type === 'video')
            <iframe width="560" height="315" src="{{ $post->content }}" frameborder="0" allowfullscreen></iframe>
        @endif
    </div>
@endsection
