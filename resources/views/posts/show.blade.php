@extends('layouts.admin')

@section('main-content')
    <div class="container">
        <h1>{{ $post->section }}</h1>
        @php
            $postData = json_decode($post->post_data);
        @endphp
        <p>Title: {{ $postData->title }}</p>
        <p>Description: {!! $postData->description !!}</p>

        <p>Content Type: {{ $postData->content_type }}</p>

        @if ($postData->content_type === 'image' && $postData->content)
            <img src="{{ asset('storage/' . $postData->content) }}" alt="Post Image" style="max-width: 35%;">
        @elseif ($postData->content_type === 'video' && $postData->content)
            <iframe width="560" height="315" src="{{ $postData->content }}" frameborder="0" allowfullscreen></iframe>
        @endif
    </div>
@endsection
