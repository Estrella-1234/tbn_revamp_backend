<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $registrations = EventRegistration::with('event')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('event', function ($query) use ($search) {
                    $query->where('judul', 'like', "%$search%");
                })
                    ->orWhere('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('affiliation', 'like', "%$search%")
                    ->orWhere('ticket_type', 'like', "%$search%")
                    ->orWhere('notes', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('attendance', $search == 'attend' ? 1 : 0);
            })
            ->paginate(10);

        return view('registrations.index', compact('registrations'));
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
            'notes' => 'nullable|string',
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
            'phone' => 'required|string|max:15',
            'affiliation' => 'required|string|max:255',
            'ticket_type' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'status' => 'required|string|max:255',
        ]);

        // Update the registration attributes
        $registration->update([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'affiliation' => $request->affiliation,
            'ticket_type' => $request->ticket_type,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        // Redirect back to the registrations index page with a success message
        return redirect()->route('registrations.index')->with('success', 'Registration updated successfully.');
    }


    public function destroy(EventRegistration $registration)
    {
        $registration->delete();
        return redirect()->route('registrations.index')->with('success', 'Registration deleted successfully.');
    }

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

    public function markAttendance(EventRegistration $registration)
    {
        $registration->attendance = true;
        $registration->save();

        return redirect()->route('registrations.index')->with('success', 'Attendance marked successfully.');
    }

    public function unmarkAttendance(EventRegistration $registration)
    {
        $registration->attendance = false;
        $registration->save();

        return redirect()->route('registrations.index')->with('success', 'Attendance unmarked successfully.');
    }

}