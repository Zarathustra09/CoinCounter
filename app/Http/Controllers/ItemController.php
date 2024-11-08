<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
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
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'image_path' => 'nullable|image',
        ]);

        $data = $request->all();
        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('images', 'public');
        }

        $item = Item::create($data);

        return response()->json($item, 201);
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required',
            'price' => 'required|numeric',
            'image_path' => 'nullable|image',
        ]);

        $data = $request->all();
        if ($request->hasFile('image_path')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image_path')->store('images', 'public');
        }

        $item->update($data);

        return response()->json($item);
    }

    // Display the specified resource.
    public function show(Item $item)
    {
        return response()->json($item);
    }

    // Show the form for editing the specified resource.
    public function edit(Item $item)
    {
        // Not needed for API
    }



    // Remove the specified resource from storage.
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
