<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * Get all of the avilibilties_days for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function avilibilties_days()
    {
        return $this->hasMany(EventAvailability::class, 'event_id');
    }

    /**
     * Get all of the avilibilties_days for the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confirmed_events()
    {
        return $this->hasMany(EventConfirm::class, 'event_id');
    }
    /**
     * Get the location that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(EventLocations::class, 'location_id');
    }

    /**
     * Get the location that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



}
