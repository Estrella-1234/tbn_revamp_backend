<?php

namespace App\Http\Controllers;

use App\Events\RegistrationStatusUpdated;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $searchType = $request->input('search_type', 'name');

        $registrations = EventRegistration::with('event')
            ->when($search, function ($query) use ($search, $searchType) {
                switch ($searchType) {
                    case 'name':
                        return $query->where('name', 'like', "%$search%");
                    case 'event':
                        return $query->whereHas('event', function ($query) use ($search) {
                            $query->where('judul', 'like', "%$search%");
                        });
                    case 'email':
                        return $query->where('email', 'like', "%$search%");
                    case 'status':
                        return $query->where('status', 'like', "%$search%");
                    case 'ticket_type':
                        return $query->where('ticket_type', 'like', "%$search%");
                    case 'attendance':
                        $attendance = $search == 'attend' ? 1 : 0;
                        return $query->where('attendance', $attendance);
                    default:
                        return $query;
                }
            })
            ->paginate(10);

        return view('registrations.index', compact('registrations'));
    }



    public function export()
    {
        $registrations = EventRegistration::all();

        $timestamp = now()->format('YmdH');
        $filename = 'registrations_' . $timestamp . '.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, array('Event', 'Name', 'Email', 'Phone', 'Affiliation', 'Ticket Type', 'Status', 'Attendance'));

            // Add data
            foreach ($registrations as $registration) {
                fputcsv($file, array(
                    $registration->event->judul,
                    $registration->name,
                    $registration->email,
                    $registration->phone,
                    $registration->affiliation,
                    $registration->ticket_type,
                    $registration->status,
                    $registration->attendance === 1 ? 'Attended' : ($registration->attendance === 0 ? 'Not Attended' : 'Unknown'),
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function create()
    {
        $events = Event::all();
        return view('registrations.create', compact('events'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:15',
            'affiliation' => 'required|string|max:255',
            'ticket_type' => 'required|string|max:50',
        ]);

        EventRegistration::create([
            'user_id' => auth()->id(),
            'event_id' => $request->event_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'affiliation' => $request->affiliation,
            'ticket_type' => $request->ticket_type,
            'notes' => $request->notes,
        ]);

        return redirect()->route('registrations.index')->with('success', 'Registration created successfully.');
    }

    public function show(EventRegistration $registration)
    {
        return view('registrations.show', compact('registration'));
    }

    public function edit(EventRegistration $registration)
    {
        $events = Event::all();
        return view('registrations.edit', compact('registration', 'events'));
    }

    public function update(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'affiliation' => 'nullable|string|max:255',
            'ticket_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:Pending,Accepted,Rejected',
            'attendance' => 'required|boolean',
        ]);

        $registration->update($request->all());
        event(new RegistrationStatusUpdated($registration));


        return redirect()->route('registrations.index')->with('success', 'Registration updated successfully.');
    }


    public function destroy(EventRegistration $registration)
    {
        $registration->delete();
        return redirect()->route('registrations.index')->with('success', 'Registration deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $registration = EventRegistration::findOrFail($id);
            $registration->status = $request->input('status');
            $registration->save();
            event(new RegistrationStatusUpdated($registration));

            return response()->json(['success' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update status', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateAttendance(Request $request, $id)
    {
        try {
            $registration = EventRegistration::findOrFail($id);
            $registration->attendance = $request->input('attendance');
            $registration->save();

            return response()->json(['success' => true, 'message' => 'Attendance updated successfully']);
        } catch (\Exception $e) {
//            Log::error('Error updating attendance: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update attendance']);
        }
    }


    //==================================================================================================================
    public function getAllData()
    {
        $registrations = EventRegistration::with('event')->get();
        return response()->json($registrations);
    }

    public function getRegistration($id)
    {
        // Attempt to find the registration by ID
        $registration = EventRegistration::with('event')->find($id);

        // If the registration is not found, return a 404 response
        if (!$registration) {
            return response()->json(['error' => 'Registration not found'], 404);
        }

        // If found, return the registration data as a JSON response
        return response()->json(['data' => $registration], 200);
    }

    public function createRegistration(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:15',
            'affiliation' => 'required|string|max:255',
            'ticket_type' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the user has already registered for the event
        $existingRegistration = EventRegistration::where('user_id', $request->user_id)
            ->where('event_id', $request->event_id)
            ->exists();

        // If the user has already registered, return an error response
        if ($existingRegistration) {
            return response()->json(['message' => 'User has already registered for this event'], 422);
        }

        // Create a new registration
        $registration = EventRegistration::create([
            'user_id' => $request->user_id,
            'event_id' => $request->event_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'affiliation' => $request->affiliation,
            'ticket_type' => $request->ticket_type,
            'notes' => $request->notes,
        ]);

        // Return success response with the created registration data
        return response()->json(['message' => 'Registration created successfully', 'data' => $registration], 201);
    }

    public function editRegistration(Request $request, $id)
    {

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:15',
            'affiliation' => 'required|string|max:255',
            'ticket_type' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'required|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the registration by ID
        $registration = EventRegistration::findOrFail($id);

        // Update the registration attributes
        $registration->user_id = $request->user_id;
        $registration->event_id = $request->event_id;
        $registration->name = $request->name;
        $registration->email = $request->email;
        $registration->phone = $request->phone;
        $registration->affiliation = $request->affiliation;
        $registration->ticket_type = $request->ticket_type;
        $registration->notes = $request->notes;
        $registration->status = $request->status;

        // Save the changes to the database
        $registration->save();

        // Return success response with the updated registration data
        return response()->json(['message' => 'Registration updated successfully', 'data' => $registration], 200);
    }

    public function deleteRegistration($id)
    {
        // Find the registration by ID
        $registration = EventRegistration::findOrFail($id);

        // Delete the registration
        $registration->delete();

        // Return a success response
        return response()->json(['message' => 'Registration deleted successfully'], 200);

    }

}
