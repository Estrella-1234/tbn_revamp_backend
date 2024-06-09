<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Add this import

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
                    ->orWhere('pembicara', 'like', "%$search%")
                    ->orWhere('lokasi', 'like', "%$search%");
            })
            ->paginate(10);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'pembicara' => 'required|string|max:255',
            'poster_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        $posterPath = $request->file('poster_path')->store('public/posters');
        $slug = Str::slug($request->judul);

        try {
            Event::create([
                'judul' => $request->judul,
                'slug' => $slug,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'pembicara' => $request->pembicara,
                'poster_path' => str_replace('public/', '', $posterPath),
                'harga' => $request->harga,
                'lokasi' => $request->lokasi
            ]);

            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation code
                return redirect()->back()->withInput()->withErrors(['slug' => 'Event already exists.']);
            }
            throw $e;
        }
    }


    public function show($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

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
            'lokasi' => 'required|string|max:255',
            'pembicara' => 'required|string|max:255',
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'harga' => 'required|string|max:255',
        ]);

        $event = Event::findOrFail($id);
        $slug = Str::slug($request->judul);

        if ($request->hasFile('poster_path')) {
            if ($event->poster_path) {
                Storage::delete('public/' . $event->poster_path);
            }

            $posterPath = $request->file('poster_path')->store('public/posters');
            $event->poster_path = str_replace('public/', '', $posterPath);
        }

        try {
            $event->update([
                'judul' => $request->judul,
                'slug' => $slug,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'lokasi' => $request->lokasi,
                'pembicara' => $request->pembicara,
                'harga' => $request->harga,
            ]);

            return redirect()->route('events.index')->with('success', 'Event updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation code
                return redirect()->back()->withInput()->withErrors(['slug' => 'Event already exists.']);
            }
            throw $e;
        }
    }


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->poster_path) {
            Storage::delete('public/' . $event->poster_path);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function getAllEvents()
    {
        $events = Event::all();

        if($events->isEmpty()) {
            return response()->json([
                'message' => 'No events found',
            ]);
        }else {
            return response()->json([
                'events' => $events,
            ]);
        }
    }

    public function getEvent($id)
    {
        $event = Event::findOrFail($id);

        return response()->json([
            'event' => $event,
        ]);
    }
}
