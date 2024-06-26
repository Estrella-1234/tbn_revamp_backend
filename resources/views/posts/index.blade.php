@extends('layouts.admin')

@section('main-content')
    <h1>All Posts</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Create New Post</a>
    <a href="{{ route('partners.index') }}" class="btn btn-secondary mb-3">Manage Partners</a>

    @if ($posts->isEmpty())
        <p>No posts found.</p>
    @else
        <table id="postsTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Section</th>
                <th>Title</th>
                <th>Description</th>
                <th>Content Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($posts as $index => $post)
                @php
                    $postData = is_array($post->post_data) ? $post->post_data : json_decode($post->post_data, true);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $post->section }}</td>
                    <td>{{ $postData['title'] }}</td>
                    <td>{!! $postData['description'] !!}</td>
                    <td>{{ $postData['content_type'] }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif

    <!-- DataTables JS Initialization -->
    <script>
        $(document).ready(function() {
            $('#postsTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                // Add any additional options here as needed
            });
        });
    </script>
@endsection
