<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/machine',[ViewController::class, 'machine'])->name('machine.blade');
Route::get('/transaction',[ViewController::class, 'transaction'])->name('transaction.blade');
Route::get('/item',[ViewController::class, 'item'])->name('item.blade');
Route::get('/inventory',[ViewController::class, 'inventory'])->name('inventory.blade');


Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
