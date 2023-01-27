<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventServiceConfirm extends Model
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
     * Get the event that owns the EventConfirm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event_confirm()
    {
        return $this->belongsTo(EventConfirm::class, 'event_confirm_id');
    }
}
