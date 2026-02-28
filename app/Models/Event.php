<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'max_capacity'];

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    public function checkedInCount()
    {
        return $this->attendees()->where('checked_in', true)->count();
    }

    public function remainingCapacity()
    {
        return $this->max_capacity - $this->checkedInCount();
    }
}
