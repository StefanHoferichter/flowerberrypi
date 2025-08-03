<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', 'App\Http\Controllers\SensorController@show_sensors');
Route::get('/remote_sockets', 'App\Http\Controllers\SensorController@show_remote_sockets');
Route::post('/remote_sockets', 'App\Http\Controllers\SensorController@control_remote_socket');
Route::get('/relays', 'App\Http\Controllers\SensorController@show_relays');
Route::post('/relays', 'App\Http\Controllers\SensorController@control_relays');
Route::get('/temperature', 'App\Http\Controllers\SensorController@show_temperature');

/*
Route::get('/', function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
require __DIR__.'/auth.php';
