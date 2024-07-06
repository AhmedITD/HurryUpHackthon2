<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MQTTController;

//comand mqtt
/////////////

//historicalRead
Route::get('/historicalRead/{device}', [MQTTController::class, 'historicalRead']);
//REALTIME
Route::get('/websocket', [MQTTController::class, 'websocket']);
//REALTIME As a test
Route::get('/realTime/{device}', [MQTTController::class, 'realTime']);
//CREUD
Route::post('/CreateDevice', [gcodeController::class, 'CreateDevice']);
Route::post('/EditeDevice/{device}', [gcodeController::class, 'EditeDevice']);
Route::post('/DeleteDevice/{device}', [gcodeController::class, 'DeleteDevice']);
Route::post('/allDevices', [gcodeController::class, 'allDevices']);