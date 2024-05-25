@extends('layouts.admin')

@section('main-content')
        <h1>Blog Posts</h1>
        <a href="{{ route('blogs.create') }}" class="btn btn-primary mb-3">Create New Blog</a>
        @if ($blogs->count())
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($blogs as $blog)
                    <tr>
                        <td><a href="{{ route('blogs.show', $blog->id) }}">{{ $blog->title }}</a></td>
                        <td class="text-center">
                            <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" style="max-width: 200px; " >
                        </td>
                        <td>{{ $blog->desc }}</td>
                        <td>{{ $blog->user->name }}</td>
                        <td>
                            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $blogs->links() }}
        @else
            <p>No blogs found.</p>
        @endif
@endsection
