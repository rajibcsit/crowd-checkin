<!DOCTYPE html>
<html>
<head>
    <title>Event List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f4f6f9;
        }

        h2 {
            margin-bottom: 15px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .form-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        input {
            padding: 8px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 15px;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .success { color: green; }
        .error { color: red; }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: white;
            flex: 1 1 calc(33.333% - 20px);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .card h3 {
            margin-top: 0;
        }

        .view-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: #28a745;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .view-btn:hover {
            background: #1e7e34;
        }

        @media(max-width: 992px) {
            .card {
                flex: 1 1 calc(50% - 20px);
            }
        }

        @media(max-width: 600px) {
            .card {
                flex: 1 1 100%;
            }
        }

    </style>
</head>

<body>

<div class="container">

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

    <div class="form-card">
        <form method="POST" action="/events">
            @csrf
            <input type="text" name="name" placeholder="Event Name" required>
            <input type="number" name="max_capacity" placeholder="Max Capacity" required>
            <button>Create Event</button>
        </form>
    </div>

    <h2>All Events</h2>

    <div class="grid">

        @forelse($events as $event)
            <div class="card">
                <h3>{{ $event->name }}</h3>
                <p><strong>Capacity:</strong> {{ $event->max_capacity }}</p>
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