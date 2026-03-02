<!DOCTYPE html>
<html>
<head>
    <title>{{ $event->name }} - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>

<div class="container">

    <a href="/">← Back to Event List</a>

    <h2>{{ $event->name }} - Dashboard</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
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
            <input type="text" name="name" placeholder="Name" >
            <input type="email" name="email" placeholder="Email" >
            <button>Add Attendee</button>
        </form>
         @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
         @endif
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
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>