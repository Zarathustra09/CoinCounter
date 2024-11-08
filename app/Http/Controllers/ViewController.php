<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function inventory()
    {
        return view('inventory.index');
    }

    public function item()
    {
        return view('item.index');
    }

    public function machine()
    {
        return view('machine.index');
    }

    public function transaction()
    {
        return view('transaction.index');
    }
}
