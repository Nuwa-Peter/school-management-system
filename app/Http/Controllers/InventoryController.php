<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $inventoryItems = InventoryItem::with('assignedTo')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(20);

        $categories = InventoryItem::select('category')->distinct()->get()->pluck('category');

        return view('inventory.index', compact('inventoryItems', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::orderBy('last_name')->get();
        return view('inventory.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_to_id' => 'nullable|exists:users,id',
            'purchase_date' => 'nullable|date',
            'value' => 'nullable|numeric',
            'serial_number' => 'nullable|string|max:255|unique:inventory_items,serial_number',
            'condition' => 'required|in:new,good,fair,poor,broken',
        ]);

        InventoryItem::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Inventory item added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryItem $inventoryItem): View
    {
        $users = User::orderBy('last_name')->get();
        return view('inventory.edit', compact('inventoryItem', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'assigned_to_id' => 'nullable|exists:users,id',
            'purchase_date' => 'nullable|date',
            'value' => 'nullable|numeric',
            'serial_number' => 'nullable|string|max:255',
            'condition' => 'required|in:new,good,fair,poor,broken',
        ]);

        $inventoryItem->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem): RedirectResponse
    {
        $inventoryItem->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }
}
