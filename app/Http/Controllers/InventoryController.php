<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $inventories = Inventory::with(['item', 'machine'])->get();
        return response()->json($inventories);
    }

    // Show the form for creating a new resource.
    public function create()
    {
        // Not needed for API
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'machine_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required|integer',
        ]);

        $inventory = Inventory::create($request->all());

        return response()->json($inventory, 201);
    }

    // Display the specified resource.
    public function show(Inventory $inventory)
    {
        return response()->json($inventory);
    }

    // Show the form for editing the specified resource.
    public function edit(Inventory $inventory)
    {
        // Not needed for API
    }

    // Update the specified resource in storage.
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'machine_id' => 'required',
            'item_id' => 'required',
            'quantity' => 'required|integer',
        ]);

        $inventory->update($request->all());

        return response()->json($inventory);
    }

    // Remove the specified resource from storage.
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return response()->json(null, 204);
    }
}
