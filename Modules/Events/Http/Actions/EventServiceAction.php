<?php

namespace Modules\Events\Http\Actions;

use App\Http\Resources\EventCollection;
use App\Models\Event;
use App\Models\EventAvailability;
use App\Models\EventConfirm;
use App\Models\EventServiceConfirm;
use Illuminate\Http\Request;

class EventServiceAction{

    public function store_service_confirm(EventConfirm $data,string $res)
    {
        $eventServiceConfirm = new EventServiceConfirm();
        $eventServiceConfirm->event_id = $data->event_id;
        $eventServiceConfirm->event_confirm_id = $data->id;
        $eventServiceConfirm->service_key = $data->location_key;
        $eventServiceConfirm->response = $res;
        $eventServiceConfirm->save();

        return $eventServiceConfirm;
    }



}
