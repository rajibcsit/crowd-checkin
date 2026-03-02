

# Event Crowd Management System (Laravel)

## Project Overview

This is a Laravel-based Event Crowd Management System that allows:

* Creating events with maximum capacity
* Adding attendees
* Checking in attendees
* Live updating crowd count without page refresh
* Displaying remaining capacity
* Showing recently checked-in attendees

The system uses Laravel Broadcasting with Pusher for real-time updates.


## Tech Stack

* Language: PHP
* Framework: Laravel
* Database: MySQL
* Frontend: Blade, HTML, CSS
* Real-time: Pusher (Laravel Broadcasting)


##  How To Run The Project

### 1️⃣ Clone the repository

```bash
git clone <your-repo-url>
cd project-folder
```

### 2️⃣ Install dependencies

```bash
composer install
npm install & npm run dev
```

### 3️⃣ Configure Environment

Copy `.env.example` to `.env`

```bash
cp .env.example .env
```

Update:

```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=sync

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_key
PUSHER_APP_SECRET=your_secret
PUSHER_APP_CLUSTER=ap2
```

### 4️⃣ Generate key

```bash
php artisan key:generate
```

### 5️⃣ Run migrations

```bash
php artisan migrate
```

### 6️⃣ Start server

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

## ✅ Completed Features

* Event creation with maximum capacity
* Attendee registration
* Unique email validation per attendee
* Capacity restriction (no check-in if full)
* Real-time crowd count updates
* Live remaining capacity updates
* Recently checked-in list (last 5)
* Clean dashboard UI
* No page refresh required for live updates


## 🔄 Real-Time Functionality

When an attendee checks in:

* Laravel Event (`CheckInUpdated`) is triggered
* Event is broadcast using Pusher
* All open dashboards receive the update instantly
* Crowd count and remaining capacity update dynamically

## 🧠 Assumptions

* No authentication system is implemented
* Public broadcasting channel is used
* Email uniqueness is enforced at database level
* Each attendee belongs to one event
* Check-in is allowed only if remaining capacity > 0


## ⚠️ Limitations

* No edit functionality for events or attendees
* No role-based access control
* No pagination for large attendee lists
* Requires valid Pusher credentials to enable real-time updates

