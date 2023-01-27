<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventConfirm extends Model
{
    use HasFactory;

    /**
     * Get the event that owns the EventConfirm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

       /**
     * Get the location that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }


       /**
     * Get the location that owns the Event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    /**
     * Get the service associated with the EventConfirm
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function service()
    {
        return $this->hasOne(EventServiceConfirm::class, 'event_confirm_id');
    }

    /**
     * Get the service associated with the EventConfirm
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function get_res()
    {
        return json_decode($this->service->response,true);
    }
}
