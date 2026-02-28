<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Dashboard</title>

    <style>
        body { font-family: Arial; margin: 30px; }
        .card {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<h2>Create Event</h2>

@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

@if($errors->any())
    <div class="error">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="/events">
    @csrf
    <input type="text" name="name" placeholder="Event Name" required>
    <input type="number" name="max_capacity" placeholder="Max Capacity" required>
    <button>Create</button>
</form>

<hr>

@foreach($events as $event)

<div class="card">

    <!-- Event Info -->
    <h3>{{ $event->name }}</h3>

    <p>Total Capacity: {{ $event->max_capacity }}</p>

    <p>
        Checked In:
        <strong id="count-{{ $event->id }}">
            {{ $event->attendees->where('checked_in', true)->count() }}
        </strong>
    </p>

    <p>
        Remaining Capacity:
        <strong id="remaining-{{ $event->id }}">
            {{ $event->max_capacity - $event->attendees->where('checked_in', true)->count() }}
        </strong>
    </p>

    <!-- Add Attendee -->
    <h4>Add Attendee</h4>
    <form method="POST" action="/events/{{ $event->id }}/attendees">
        @csrf
        <input name="name" placeholder="Attendee Name" required>
        <input name="email" placeholder="Email" required>
        <button>Add</button>
    </form>

    <hr>

    <!-- Recently Checked-in Attendees -->
    <h4>Recently Checked-In</h4>
    <ul id="recent-{{ $event->id }}">
        @foreach($event->attendees->where('checked_in', true)->sortByDesc('updated_at')->take(5) as $attendee)
            <li>{{ $attendee->name }} ({{ $attendee->email }})</li>
        @endforeach
    </ul>

</div>

@endforeach


<!-- Pusher Script -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>

var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: true
});

@foreach($events as $event)

var channel{{ $event->id }} = pusher.subscribe("event.{{ $event->id }}");

channel{{ $event->id }}.bind("CheckInUpdated", function(data) {

    let checkedInCount = data.checked_in_count;
    let remaining = data.remaining_capacity;

    document.getElementById("count-{{ $event->id }}").innerText = checkedInCount;
    document.getElementById("remaining-{{ $event->id }}").innerText = remaining;

    let list = document.getElementById("recent-{{ $event->id }}");

    let newItem = document.createElement("li");
    newItem.innerText = data.last_checked_in.name + " (" + data.last_checked_in.email + ")";
    list.prepend(newItem);

    if (list.children.length > 5) {
        list.removeChild(list.lastChild);
    }

});

@endforeach

</script>

</body>
</html>