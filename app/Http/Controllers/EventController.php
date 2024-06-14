<?php
namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

// Add this import

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
            'poster_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120', // Allow typical image formats
            'harga' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        // Handle image upload and conversion to WebP
        if ($request->hasFile('poster_path')) {
            $image = $request->file('poster_path');

            // Generate a unique filename for the WebP image
            $webpFileName = time() . '.webp'; // Use .webp extension explicitly
            $webpFilePath = 'public/posters/' . $webpFileName;

            // Use Intervention Image to convert and save as WebP
            $webpImage = Image::make($image)
                ->encode('webp', 80); // Specify WebP format and quality

            // Save the WebP image to storage
            Storage::put($webpFilePath, (string) $webpImage->encode());

            // Store the WebP file path for database insertion
            $posterPath = str_replace('public/', '', $webpFilePath);
        }

        $slug = Str::slug($request->judul);

        try {
            Event::create([
                'judul' => $request->judul,
                'slug' => $slug,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'pembicara' => $request->pembicara,
                'poster_path' => $posterPath ?? null, // Use the WebP file path if uploaded
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
            'poster_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'harga' => 'required|string|max:255',
        ]);

        $event = Event::findOrFail($id);
        $slug = Str::slug($request->judul);

        // Handle image upload and conversion to WebP
        if ($request->hasFile('poster_path')) {
            // Delete old poster if it exists
            if ($event->poster_path) {
                Storage::delete('public/' . $event->poster_path);
            }

            // Convert and save the uploaded image as WebP
            $image = $request->file('poster_path');
            $webpFileName = time() . '.' . $image->getClientOriginalExtension();
            $webpFilePath = 'public/posters/' . $webpFileName;

            Image::make($image)
                ->encode('webp', 80) // Convert to WebP with 80% quality
                ->save(storage_path('app/' . $webpFilePath));

            $posterPath = str_replace('public/', '', $webpFilePath);
            $event->poster_path = $posterPath;
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

    public function getbySlug($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        return response()->json(['event' => $event]);
    }

    public function getbyId($id)
    {
        $event = Event::where('id', $id)->firstOrFail();

        return response()->json(['event' => $event]);
    }
}
