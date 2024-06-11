@extends('layouts.admin')

@section('main-content')
    <h1>Event Registrations</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Alert -->
    <div id="success-alert" class="alert alert-success" style="display: none;">
        <strong>Success!</strong> <span id="success-message"></span>
    </div>

    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('registrations.create') }}" class="btn btn-primary mr-3">Register for an Event</a>
        <a href="{{ route('registrations.export') }}" class="btn btn-secondary mr-auto">Export CSV</a>

        <form action="{{ route('registrations.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="event" class="form-control mr-2" placeholder="Event" value="{{ request()->input('event') }}">
                <input type="text" name="name" class="form-control mr-2" placeholder="Name" value="{{ request()->input('name') }}">
                <input type="text" name="email" class="form-control mr-2" placeholder="Email" value="{{ request()->input('email') }}">
                <input type="text" name="status" class="form-control mr-2" placeholder="Status" value="{{ request()->input('status') }}">
                <input type="text" name="attendance" class="form-control mr-2" placeholder="Attendance" value="{{ request()->input('attendance') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>

    @if($registrations->count())
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>No</th>
                <th>Event</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Affiliation</th>
                <th>Ticket Type</th>
                <th>Status</th>
                <th>Attendance</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($registrations as $index => $registration)
                <tr>
                    <td>{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $index + 1 }}</td>
                    <td>{{ $registration->event->judul }}</td>
                    <td>{{ $registration->name }}</td>
                    <td>{{ $registration->email }}</td>
                    <td>{{ $registration->phone }}</td>
                    <td>{{ $registration->affiliation }}</td>
                    <td>{{ $registration->ticket_type }}</td>
                    <td>
                        <select class="form-control status-select" data-id="{{ $registration->id }}">
                            <option value="Accepted" {{ $registration->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="Pending" {{ $registration->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Rejected" {{ $registration->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control attendance-select" data-id="{{ $registration->id }}">
                            <option value="1" {{ $registration->attendance === 1 ? 'selected' : '' }}>Attended</option>
                            <option value="0" {{ $registration->attendance === 0 ? 'selected' : '' }}>Not Attended</option>
                        </select>
                    </td>
                    <td>
                        <a href="{{ route('registrations.show', $registration->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('registrations.edit', $registration->id) }}" class="btn btn-warning">Edit</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteRegistrationModal-{{ $registration->id }}">
                            Delete
                        </button>
                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteRegistrationModal-{{ $registration->id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="deleteRegistrationModalLabel-{{ $registration->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteRegistrationModalLabel-{{ $registration->id }}">Confirm Deletion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the registration for {{ $registration->name }} (ID: {{ $registration->id }})? This action cannot be undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <form action="{{ route('registrations.destroy', $registration->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $registrations->links() }}
    @else
        <p>No registrations found.</p>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusSelects = document.querySelectorAll('.status-select');
            const attendanceSelects = document.querySelectorAll('.attendance-select');
            const successAlert = document.getElementById('success-alert');
            const successMessage = document.getElementById('success-message');

            statusSelects.forEach(select => {
                select.addEventListener('change', function () {
                    const registrationId = this.getAttribute('data-id');
                    const newStatus = this.value;

                    fetch(`/registrations/${registrationId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                successMessage.textContent = 'Status updated successfully';
                                successAlert.style.display = 'block';
                                setTimeout(() => {
                                    successAlert.style.display = 'none';
                                }, 3000);
                            } else {
                                alert('Failed to update status');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while updating status');
                        });
                });
            });

            attendanceSelects.forEach(select => {
                select.addEventListener('change', function () {
                    const registrationId = this.getAttribute('data-id');
                    const newAttendance = this.value;

                    fetch(`/registrations/${registrationId}/attendance`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ attendance: newAttendance })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                successMessage.textContent = 'Attendance updated successfully';
                                successAlert.style.display = 'block';
                                setTimeout(() => {
                                    successAlert.style.display = 'none';
                                }, 3000);
                            } else {
                                alert('Failed to update attendance');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while updating attendance');
                        });
                });
            });
        });
    </script>
@endsection
