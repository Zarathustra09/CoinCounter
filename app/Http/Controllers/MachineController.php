<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MachineController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $machines = Machine::all();
        return response()->json([
            'data' => $machines
        ]);
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
            'location' => 'required|string',
            'identifier' => 'required|string',
        ]);

        $machine = Machine::create($request->all());

        return response()->json($machine, 201);
    }

    // Display the specified resource.
    public function show(Machine $machine)
    {
        return response()->json($machine);
    }

    // Show the form for editing the specified resource.
    public function edit(Machine $machine)
    {
        // Not needed for API
    }

    // Update the specified resource in storage.
    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'location' => 'required|string',
            'identifier' => 'required|string',
        ]);

        $machine->update($request->all());

        return response()->json($machine);
    }

    // Remove the specified resource from storage.
    public function destroy(Machine $machine)
    {
        $machine->delete();

        return response()->json(null, 204);
    }
}
