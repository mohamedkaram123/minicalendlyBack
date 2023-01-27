<?php

namespace Modules\Events\Http\Actions;

use App\Http\Resources\EventCollection;
use App\Models\Event;
use App\Models\EventAvailability;
use Illuminate\Http\Request;

class EventAction{

    public function store_event(Request $req)
    {
        $event = new Event();
        $event->name = $req->name;
        $event->link = $this->get_link_unique($req);
        $event->user_id = auth("api")->user()->id;
        $event->disc = $req->disc;
        $event->location_id = $req->location_id;
        $event->save();

        return $event;
    }

    public function turn_event($req)
    {
        $event = Event::find($req->id);
        $event->active = $req->active;
        $event->save();

        return $event;
    }

    public function get_link_unique(Request $req)
    {
        //$link = !empty($req->link)?$req->link:$req->name;
        $link_count = Event::where("link",$req->link)->count();

        return ($link_count > 0?"$req->link-$link_count":$req->link);
    }

    public function update_event(Request $req)
    {
        $event = Event::find($req->id);

            $event->duration = $req->duration;
            $event->duration_type = $req->duration_type;
            $event->date_range = $req->date_range;
            $event->date_range_custom = $req->date_range_custom;


        $event->save();

        return $event;
    }
    public function update_event_level1(Request $req)
    {
        $event = Event::find($req->id);

            $event->name = $req->name;
            $event->link = $this->get_link_unique($req);
            $event->user_id = auth("api")->user()->id;
            $event->disc = $req->disc;
            $event->location_id = $req->location_id;

        $event->save();

        return $event;
    }



    public function get_user_events()
    {
        $user = auth("api")->user();

        $events = Event::where("user_id",$user->id)->orderByDesc("id")->get();
        return $events;
    }

    public function get_event(Request $req)
    {

        $event = Event::where("link",$req->slug)->first();
        return $event;
    }


}
