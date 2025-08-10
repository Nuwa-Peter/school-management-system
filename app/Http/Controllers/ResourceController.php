<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $resources = Resource::latest()->get();
        return view('resources.index', compact('resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Resource::create($request->all());

        return redirect()->route('resources.index')->with('success', 'Resource created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $resource->update($request->all());

        return redirect()->route('resources.index')->with('success', 'Resource updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        $resource->delete();
        return redirect()->route('resources.index')->with('success', 'Resource deleted successfully.');
    }
}
