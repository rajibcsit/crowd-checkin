<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'max_capacity' => 'required|integer|min:1'
        ]);

        Event::create($request->all());

        return back()->with('success', 'Event created!');
    }
}
