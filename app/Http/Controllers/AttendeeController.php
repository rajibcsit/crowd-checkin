<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendeeController extends Controller
{
    public function store(Request $request, Event $event)
        {
            $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('attendees')
                    ->where(function ($query) use ($event) {
                        return $query->where('event_id', $event->id);
                    })
            ],
        ], [
            'email.unique' => 'This email is already registered for this event.'
        ]);

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
