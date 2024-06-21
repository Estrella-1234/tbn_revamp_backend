@extends('layouts.admin')

@section('main-content')
    <h1>Blog Posts</h1>
    <a href="{{ route('blogs.create') }}" class="btn btn-primary mb-3">Create New Blog</a>
    @if ($blogs->count())
        <table id="blogsTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Image</th>
                <th>Description</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($blogs as $index => $blog)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a></td>
                    <td class="text-center">
                        <img src="{{ asset('storage/' . $blog->image_path) }}" alt="{{ $blog->title }}" style="max-width: 200px;">
                    </td>
                    <td>{!! $blog->desc !!}</td>
                    <td>{{ $blog->user->name }}</td>
                    <td>
                        <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                        <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this blog?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else
        <p>No blogs found.</p>
    @endif

    <script>
        $(document).ready(function() {
            $('#blogsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100]
            });
        });
    </script>
@endSection
