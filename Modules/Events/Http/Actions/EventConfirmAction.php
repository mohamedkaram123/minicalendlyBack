<?php

namespace Modules\Events\Http\Actions;

use App\Models\Event;
use App\Models\EventAvailability;
use App\Models\EventConfirm;
use App\Models\EventLocations;
use App\Models\Guest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EventConfirmAction
{

    public function store(Request $req,$guest,$host)
    {

            $event = Event::find($req->event_id);
        $avilibity_day = $event->avilibilties_days->where("day",$req->day)->first();

        $event_confirm = new EventConfirm();
        $event_confirm->guest_id = $guest->id;
        $event_confirm->event_id = $req->event_id;

        $event_confirm->host_id = $host->id;
        $event_confirm->event_availibilty_id = $avilibity_day->id;
        $event_confirm->duration_type = $event->duration_type;
        $event_confirm->duration = $event->duration;

        $event_confirm->event_date = $req->event_date;
        $event_confirm->notes = $req->notes;
        $event_confirm->location_key = $event->location->key;

        $event_confirm->save();

        return $event_confirm;

    }

    public function register_guest(Request $request){

        $user = new Guest();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return $user;
    }

    public function check_confirmed_events($event,$times_model)
    {


        $data = [];
        $times_model = $times_model[0];

        foreach ($event->confirmed_events as $item) {
           $time_date = (new Carbon($item->event_date))->format("H:i");// Carbon::createFromFormat('H:i:s',$item->event_date );
           foreach ($times_model as $key => $object) {

                $time = substr($object["value"],0,5) ;

                if ($time_date == $time) {
                    array_splice($times_model, $key, 1);
                    $data = $times_model;

                   }
                }

        }

        return $times_model;
    }
}
