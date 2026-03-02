<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount([
            'attendees as checked_in_count' => function ($q) {
                $q->where('checked_in', true);
            }
        ])->get();

        return view('events.list', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('attendees');
        return view('events.show', compact('event'));
    }

    public function store(EventRequest $request)
    {
        Event::create($request->validated());

        return back()->with('success', 'Event created!');
    }
}
