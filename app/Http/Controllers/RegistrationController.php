<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = EventRegistration::with('event')->paginate(10);
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


}
