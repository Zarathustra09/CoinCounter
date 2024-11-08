<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\Inventory;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $transactions = Transaction::with(['item', 'machine'])->get();
        return response()->json($transactions);
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
            'machine_id' => 'required|exists:machines,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'purchased_at' => 'required|date',
        ]);

        // Check inventory stock
        $inventory = Inventory::where('machine_id', $request->machine_id)
            ->where('item_id', $request->item_id)
            ->first();

        if (!$inventory || $inventory->quantity < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock in inventory'], 400);
        }

        // Fetch the item's price
        $item = Item::findOrFail($request->item_id);
        $total_price = $item->price * $request->quantity;

        // Create the transaction with the calculated total price
        $transaction = Transaction::create([
            'machine_id' => $request->machine_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
            'purchased_at' => $request->purchased_at,
        ]);

        // Update inventory
        $inventory->quantity -= $request->quantity;
        $inventory->save();

        return response()->json($transaction, 201);
    }


    // Display the specified resource.
    public function show(Transaction $transaction)
    {
        return response()->json($transaction);
    }

    // Show the form for editing the specified resource.
    public function edit(Transaction $transaction)
    {
        // Not needed for API
    }

    // Update the specified resource in storage.
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer',
            'total_price' => 'required|numeric',
            'purchased_at' => 'required|date',
        ]);

        // Update inventory
        $inventory = Inventory::where('machine_id', $transaction->machine_id)
            ->where('item_id', $transaction->item_id)
            ->first();

        if ($inventory) {
            $inventory->quantity += $transaction->quantity; // Add back the old quantity
            $inventory->quantity -= $request->quantity; // Deduct the new quantity
            $inventory->save();
        }

        $transaction->update($request->all());

        return response()->json($transaction);
    }

    // Remove the specified resource from storage.
    public function destroy(Transaction $transaction)
    {
        // Update inventory
        $inventory = Inventory::where('machine_id', $transaction->machine_id)
            ->where('item_id', $transaction->item_id)
            ->first();

        if ($inventory) {
            $inventory->quantity += $transaction->quantity; // Add back the quantity
            $inventory->save();
        }

        $transaction->delete();

        return response()->json(null, 204);
    }
}
