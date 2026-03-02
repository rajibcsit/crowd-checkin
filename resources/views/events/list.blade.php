<!DOCTYPE html>
<html>
<head>
    <title>Event List</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>

<div class="container">

    <h2>Create Event</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <div class="form-card">
        <form method="POST" action="/events">
            @csrf
            <input type="text" name="name" placeholder="Event Name">
            <input type="number" name="max_capacity" placeholder="Max Capacity">
            <button>Create Event</button>
        </form>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <h2>All Events</h2>

    <div class="grid">

        @forelse($events as $event)
            <div class="card">
                <h3>{{ $event->name }}</h3>
                <p><strong>Capacity:</strong> {{ $event->max_capacity }}</p>
                <p><strong>Checked In:</strong> {{ $event->checked_in_count }}</p>
                <a href="/events/{{ $event->id }}" class="view-btn">
                    View Dashboard →
                </a>
            </div>
        @empty
            <p>No events created yet.</p>
        @endforelse

    </div>

</div>

</body>
</html>