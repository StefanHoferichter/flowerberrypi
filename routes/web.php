<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', 'App\Http\Controllers\SensorController@show_home');
Route::get('/sensors', 'App\Http\Controllers\SensorController@show_sensors');
Route::get('/remote_sockets', 'App\Http\Controllers\SensorController@show_remote_sockets');
Route::post('/remote_sockets', 'App\Http\Controllers\SensorController@control_remote_socket');
Route::get('/relays', 'App\Http\Controllers\SensorController@show_relays');
Route::post('/relays', 'App\Http\Controllers\SensorController@control_relays');
Route::get('/temperatures', 'App\Http\Controllers\SensorController@show_temperatures');
Route::get('/distances', 'App\Http\Controllers\SensorController@show_distances');
Route::get('/soil_moistures', 'App\Http\Controllers\SensorController@show_soil_moistures');
Route::get('/camera', 'App\Http\Controllers\SensorController@show_camera');
Route::post('/camera', 'App\Http\Controllers\SensorController@make_picture');
Route::get('/jobs', 'App\Http\Controllers\SensorController@show_jobs');
Route::get('/job_details/{id}', 'App\Http\Controllers\SensorController@show_job_details');
Route::post('/trigger_job', 'App\Http\Controllers\SensorController@triggerJob');
Route::get('/forecast', 'App\Http\Controllers\ForecastController@read_daily_api');
Route::get('/zones', 'App\Http\Controllers\SensorController@show_zones');
Route::get('/zone_details/{id}', 'App\Http\Controllers\SensorController@show_zone_details');
Route::get('/manual_watering', 'App\Http\Controllers\SensorController@show_manual_watering');
Route::post('/manual_watering', 'App\Http\Controllers\SensorController@show_manual_watering2');
Route::get('/setup', 'App\Http\Controllers\SetupController@show_setup');
Route::get('/setup_percentage_conversions', 'App\Http\Controllers\SetupController@show_percentage_conversions');
Route::post('/setup_percentage_conversions', 'App\Http\Controllers\SetupController@save_percentage_conversions');
Route::get('/setup_remote_sockets', 'App\Http\Controllers\SetupController@show_remote_sockets');
Route::post('/setup_remote_sockets', 'App\Http\Controllers\SetupController@save_remote_sockets');
Route::get('/setup_sensors', 'App\Http\Controllers\SetupController@show_sensors');
Route::post('/setup_sensors', 'App\Http\Controllers\SetupController@save_sensors');
Route::get('/setup_thresholds', 'App\Http\Controllers\SetupController@show_thresholds');
Route::post('/setup_thresholds', 'App\Http\Controllers\SetupController@save_thresholds');
Route::get('/setup_zones', 'App\Http\Controllers\SetupController@show_zones');
Route::post('/setup_zones', 'App\Http\Controllers\SetupController@save_zones');
Route::get('/diagnosis', 'App\Http\Controllers\DiagnosisController@show_diagnosis');
Route::get('/i2c_bus', 'App\Http\Controllers\DiagnosisController@show_i2c_bus');
Route::get('/sniff', 'App\Http\Controllers\DiagnosisController@show_433mhz_start');
Route::post('/sniff', 'App\Http\Controllers\DiagnosisController@show_433mhz');

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
