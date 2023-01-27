<?php

namespace Modules\Events\Http\Actions;

use App\Models\Event;
use App\Models\EventAvailability;
use App\Models\EventLocations;
use Illuminate\Http\Request;

class EventLocationAction
{


    public function get_locations()
    {
      $locations = EventLocations::get();
      return $locations;
    }

}
