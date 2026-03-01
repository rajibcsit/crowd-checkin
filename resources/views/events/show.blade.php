<!DOCTYPE html>
<html>
<head>
    <title>{{ $event->name }} - Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px;
            background: #f4f6f9;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h2 {
            margin-bottom: 20px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        .grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .stat-card {
            flex: 1;
            min-width: 250px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
        }

        .stat-card h3 {
            margin: 0;
            font-size: 26px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        input {
            padding: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        button {
            padding: 7px 14px;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .checkin-btn {
            background: #28a745;
        }

        .checkin-btn:hover {
            background: #1e7e34;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            background: #28a745;
            color: white;
        }

        .full-badge {
            background: #dc3545;
        }

        .attendee-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .success { color: green; }
        .error { color: red; }

    </style>
</head>

<body>

<div class="container">

    <a href="/">← Back to Event List</a>

    <h2>{{ $event->name }} - Dashboard</h2>

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

    <!-- Status Badge -->
    @if($event->remainingCapacity() > 0)
        <span class="badge">Available</span>
    @else
        <span class="badge full-badge">Full</span>
    @endif

    <!-- Statistics Cards -->
    <div class="grid">

        <div class="stat-card">
            <p>Total Capacity</p>
            <h3>{{ $event->max_capacity }}</h3>
        </div>

        <div class="stat-card">
            <p>Checked-In</p>
            <h3 id="count">{{ $event->checkedInCount() }}</h3>
        </div>

        <div class="stat-card">
            <p>Remaining</p>
            <h3 id="remaining">{{ $event->remainingCapacity() }}</h3>
        </div>

    </div>

    <!-- Add Attendee -->
    <div class="card">
        <h3>Add Attendee</h3>
        <form method="POST" action="/events/{{ $event->id }}/attendees">
            @csrf
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <button>Add Attendee</button>
        </form>
    </div>

    <!-- All Attendees -->
    <div class="card">
        <h3>All Attendees</h3>

        @forelse($event->attendees as $attendee)
            <div class="attendee-item">
                <div>
                    {{ $attendee->name }} <br>
                    <small>{{ $attendee->email }}</small>
                </div>

                @if(!$attendee->checked_in)
                    <form method="POST" action="/attendees/{{ $attendee->id }}/checkin">
                        @csrf
                        <button class="checkin-btn">Check In</button>
                    </form>
                @else
                    <span class="badge">Checked In</span>
                @endif
            </div>
        @empty
            <p>No attendees yet.</p>
        @endforelse
    </div>

    <!-- Recently Checked-In -->
    <div class="card">
        <h3>Recently Checked-In</h3>
        <ul id="recent">
            @foreach($event->attendees->where('checked_in', true)->sortByDesc('updated_at')->take(5) as $attendee)
                <li>{{ $attendee->name }} ({{ $attendee->email }})</li>
            @endforeach
        </ul>
    </div>

</div>

<!-- Pusher -->
<script>
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
    });

var channel = pusher.subscribe("event.{{ $event->id }}");

channel.bind("CheckInUpdated", function(data) {

    document.getElementById("count").innerText = data.checked_in_count;
    document.getElementById("remaining").innerText = data.remaining_capacity;

    let badge = document.getElementById("status-badge");
    if (data.remaining_capacity > 0) {
        badge.innerText = "Available";
        badge.classList.remove("full-badge");
        badge.classList.add("badge");
    } else {
        badge.innerText = "Full";
        badge.classList.remove("badge");
        badge.classList.add("full-badge");
    }

    if (data.last_checked_in) {
        let list = document.getElementById("recent");

        let item = document.createElement("li");
        item.innerText = data.last_checked_in.name + " (" + data.last_checked_in.email + ")";
        list.prepend(item);

        if (list.children.length > 5) {
            list.removeChild(list.lastChild);
        }
    }

});
</script>

</body>
</html>