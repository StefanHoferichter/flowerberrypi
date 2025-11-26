<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', 'App\Http\Controllers\SystemController@show_home');
    Route::get('/reboot', 'App\Http\Controllers\SystemController@reboot');
    Route::get('/shutdown', 'App\Http\Controllers\SystemController@shutdown');
    Route::get('/impressum', 'App\Http\Controllers\SystemController@show_impressum');
    
    Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
    
    Route::get('/sensors', 'App\Http\Controllers\SensorController@show_sensors');
    Route::get('/remote_sockets', 'App\Http\Controllers\SensorController@show_remote_sockets');
    Route::post('/433mhz_sockets', 'App\Http\Controllers\SensorController@control_433mhz_socket');
    Route::post('/wifi_sockets', 'App\Http\Controllers\SensorController@control_wifi_socket');
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
    Route::post('/track_watering', 'App\Http\Controllers\SensorController@track_watering');
    Route::get('/triggered_watering', 'App\Http\Controllers\SensorController@show_triggered_watering');
    Route::post('/trigger_watering', 'App\Http\Controllers\SensorController@trigger_watering');
    Route::get('/automated_watering', 'App\Http\Controllers\SensorController@show_automated_watering');
    
    Route::get('/setup', 'App\Http\Controllers\SetupController@show_setup');
    Route::get('/setup_percentage_conversions', 'App\Http\Controllers\SetupController@show_percentage_conversions');
    Route::post('/setup_percentage_conversions', 'App\Http\Controllers\SetupController@save_percentage_conversions');
    Route::get('/setup_remote_sockets', 'App\Http\Controllers\SetupController@show_remote_sockets');
    Route::post('/setup_433mhz_sockets', 'App\Http\Controllers\SetupController@save_433mhz_sockets');
    Route::post('/setup_wifi_sockets', 'App\Http\Controllers\SetupController@save_wifi_sockets');
    Route::get('/setup_sensors', 'App\Http\Controllers\SetupController@show_sensors');
    Route::post('/setup_sensors', 'App\Http\Controllers\SetupController@save_sensors');
    Route::get('/setup_thresholds', 'App\Http\Controllers\SetupController@show_thresholds');
    Route::post('/setup_thresholds', 'App\Http\Controllers\SetupController@save_thresholds');
    Route::get('/setup_zones', 'App\Http\Controllers\SetupController@show_zones');
    Route::post('/setup_zones', 'App\Http\Controllers\SetupController@save_zones');
    Route::get('/setup_misc', 'App\Http\Controllers\SetupController@show_misc');
    Route::post('/setup_password', 'App\Http\Controllers\SetupController@save_password');
    Route::post('/setup_location', 'App\Http\Controllers\SetupController@save_location');
    
    Route::get('/diagnosis', 'App\Http\Controllers\DiagnosisController@show_diagnosis');
    Route::get('/i2c_bus', 'App\Http\Controllers\DiagnosisController@show_i2c_bus');
    Route::get('/sniff', 'App\Http\Controllers\DiagnosisController@show_433mhz_start');
    Route::post('/sniff', 'App\Http\Controllers\DiagnosisController@show_433mhz');
});


Route::middleware('guest')->group(function () {
//    Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');
});

//require __DIR__.'/auth.php';
