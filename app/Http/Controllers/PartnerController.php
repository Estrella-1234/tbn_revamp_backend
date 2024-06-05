<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::all();
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
            'image' => 'required|image',
        ]);

        $path = $request->file('image')->store('partners', 'public');

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
            'image' => 'image',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('partners', 'public');
            $partner->update(['image' => $path]);
        }

        $partner->update(['name' => $request->name]);

        return redirect()->route('partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('partners.index')->with('success', 'Partner deleted successfully.');
    }

    public function getallpartners()
    {
        $partners = Partner::all();
        return response()->json($partners);
    }
}
