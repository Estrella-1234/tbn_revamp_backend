<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $events = Event::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('judul', 'like', "%$search%")
                    ->orWhere('deskripsi', 'like', "%$search%")
                    ->orWhere('tanggal', 'like', "%$search%")
                    ->orWhere('pembicara', 'like', "%$search%");
            })
            ->paginate(10);

        return view('events.index', compact('events'));

    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'pembicara' => 'required|string|max:255',
            'poster_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $posterPath = $request->file('poster_path')->store('public/posters');

        Event::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'pembicara' => $request->pembicara,
            'poster_path' => str_replace('public/', '', $posterPath),
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'pembicara' => 'required|string|max:255',
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $event = Event::findOrFail($id);

        if ($request->hasFile('poster_path')) {
            // Delete the old image if it exists
            if ($event->poster_path) {
                Storage::delete('public/' . $event->poster_path);
            }

            // Store the new image
            $posterPath = $request->file('poster_path')->store('public/posters');
            $event->poster_path = str_replace('public/', '', $posterPath);
        }

        $event->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'pembicara' => $request->pembicara,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Delete the image associated with the event
        if ($event->poster_path) {
            Storage::delete('public/' . $event->poster_path);
        }

        // Delete the event from the database
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function getAllEvents()
    {
        $events = Event::all();

        return response()->json([
            'events' => $events,
        ]);
    }

    public function getEvent($id)
    {
        $event = Event::findOrFail($id);

        return response()->json([
            'event' => $event,
        ]);
    }

}
