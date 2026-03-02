<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendeeRequest;
use App\Models\Attendee;
use App\Models\Event;

class AttendeeController extends Controller
{
    public function store(AttendeeRequest $request, Event $event)
        {
        if ($event->remainingCapacity() <= 0) {
            return back()->withErrors('Event is full.');
        }

        Attendee::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Attendee added.');
    }

    public function checkIn(Attendee $attendee)
    {
        if ($attendee->checked_in) {
            return back()->withErrors('Already checked in.');
        }

        if ($attendee->event->remainingCapacity() <= 0) {
            return back()->withErrors('Event is full.');
        }

        $attendee->update(['checked_in' => true]);

        broadcast(new \App\Events\CheckInUpdated($attendee->event))->toOthers();
        
        return back()->with('success', 'Checked in successfully.');
    }
}
