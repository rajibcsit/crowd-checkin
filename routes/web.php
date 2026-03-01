<?php

use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;



// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);

Route::post('/events/{event}/attendees', [AttendeeController::class, 'store']);
Route::post('/attendees/{attendee}/checkin', [AttendeeController::class, 'checkIn']);
