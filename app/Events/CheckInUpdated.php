<?php

namespace App\Events;

use App\Models\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckInUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    /**
     * Create a new event instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('event.'.$this->event->id);

    }

    public function broadcastWith()
    {
        return [
            'checked_in_count' => $this->event->attendees()->where('checked_in', true)->count(),
            'remaining_capacity' => $this->event->remainingCapacity(),
            'last_checked_in' => [
                'name' => $this->event->attendees()->latest()->first()->name,
                'email' => $this->event->attendees()->latest()->first()->email,
            ]
        ];
    }
}
