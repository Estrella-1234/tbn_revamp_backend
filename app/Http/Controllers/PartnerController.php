<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::query()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('partners.index', compact('partners'));
    }


    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|max:5120',
        ]);

        // Handle image upload and conversion to WebP
        if ($request->hasFile('image')) {
            // Load the image
            $image = Image::make($request->file('image'));

            // Compress and convert to WebP
            $webpPath = 'partners/' . uniqid() . '.webp';
            $image->encode('webp', 80)->save(storage_path('app/public/' . $webpPath));

            $path = $webpPath;
        } else {
            $path = null;
        }

        Partner::create([
            'name' => $request->name,
            'image' => $path,
        ]);

        return redirect()->route('partners.index')->with('success', 'Partner created successfully.');
    }


    public function show(Partner $partner)
    {
        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Load the image
            $image = Image::make($request->file('image'));

            // Compress and convert to WebP
            $webpPath = 'partners/' . uniqid() . '.webp';
            $image->encode('webp', 80)->save(storage_path('app/public/' . $webpPath));

            // Delete the old image
            Storage::disk('public')->delete($partner->image);

            // Update the partner with the new image path
            $partner->update(['image' => $webpPath]);
        }

        // Update the partner's name
        $partner->update(['name' => $request->name]);

        return redirect()->route('partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        // Delete the associated image file from storage
        if ($partner->image) {
            Storage::disk('public')->delete($partner->image);
        }

        // Delete the partner record from the database
        $partner->delete();

        return redirect()->route('partners.index')->with('success', 'Partner deleted successfully.');
    }

    public function getallpartners()
    {
        $partners = Partner::all();
        return response()->json($partners);
    }
}
