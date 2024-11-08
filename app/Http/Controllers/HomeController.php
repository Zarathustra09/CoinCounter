<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $salesToday = Transaction::whereDate('purchased_at', $today)->sum('total_price');
        $salesThisWeek = Transaction::whereBetween('purchased_at', [$startOfWeek, Carbon::now()])->sum('total_price');
        $salesThisMonth = Transaction::whereBetween('purchased_at', [$startOfMonth, Carbon::now()])->sum('total_price');
        $salesThisYear = Transaction::whereBetween('purchased_at', [$startOfYear, Carbon::now()])->sum('total_price');

        return view('home', compact('salesToday', 'salesThisWeek', 'salesThisMonth', 'salesThisYear'));
    }
}
