@extends('layouts.admin')

@section('main-content')
    <h1>All Partners</h1>
    <a href="{{ route('partners.create') }}" class="btn btn-primary mb-3">Create New Partner</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="partnersTable" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($partners as $index => $partner)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $partner->name }}</td>
                <td><img src="{{ asset('storage/' . $partner->image) }}" alt="{{ $partner->name }}" width="50"></td>
                <td>
                    <a href="{{ route('partners.edit', $partner) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('partners.destroy', $partner) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this partner?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <!-- DataTables JS Initialization -->
    <script>
        $(document).ready(function() {
            $('#partnersTable').DataTable({
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
