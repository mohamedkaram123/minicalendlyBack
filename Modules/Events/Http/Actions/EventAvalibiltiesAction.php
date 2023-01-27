<?php

namespace Modules\Events\Http\Actions;

use App\Models\Event;
use App\Models\EventAvailability;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventAvalibiltiesAction
{


    public function make_minicalendly_availibilities($event)
    {
      $days = ["sunday","monday","tuesday","wednesday","thursday"];
      $times = [[
        "from_time"=>"09:00:00",
        "to_time"=>"17:00:00",
         ]];

      $day_group = [];
      foreach ($days as $day) {
          $event_avalibilty = new EventAvailability();
          $event_avalibilty->event_id = $event->id;
          $event_avalibilty->day = $day;
          $event_avalibilty->times = json_encode($times);
          $event_avalibilty->save();

          $day_group[] = $event_avalibilty;

      }

      return $day_group;
    }


    public function store_minicalendly_availibilities(Request $req)
    {
      $day_group = [];
      foreach ($req->avalibility as $item) {
          $event_avalibilty = new EventAvailability();
          $event_avalibilty->event_id = $req->id;
          $event_avalibilty->day = $item["day"];
          $event_avalibilty->times = json_encode($item["times"]);
          $event_avalibilty->save();

          $day_group[] = $event_avalibilty;
      }

      return $day_group;
    }

    public function delete_minicalendly_availibilities($event)
    {
        foreach ($event->avilibilties_days as $key => $event_avalibilty) {
            $event_avalibilty->delete();
        }
    }

    public function from_to_time($from,$to,$duration,$duration_type)
    {

       $from_time  = Carbon::createFromFormat('H:i',substr($from,0,5));
       $to_time  = Carbon::createFromFormat('H:i',substr($to,0,5));
       $value = $from_time->format('H:i');
       $label = $from_time->format('h:ia');

        $all_data = [];
        $all_data[] = ["value"=>$value,"label"=>$label];

        while ($from_time <  $to_time ) {
            $from_time = $duration_type == "m"? $from_time->addMinutes($duration):$from_time->addHours($duration);
            $value = $from_time->format('H:i');
            $label = $from_time->format('h:ia');

            if($to_time->format('H:i') != $value && $from_time <  $to_time ){
                $all_data[] = ["value"=>$value,"label"=>$label];

            }


        }

        return $all_data;

    }


    public function make_start_date($event)
    {
        $days_diff_keys = [];
        foreach ($event->avilibilties_days as $key => $event_avalibilty) {
            $next_date = new Carbon($event_avalibilty->day);
            $now = Carbon::now();

            $diff_days = $next_date->diffInDays($now);
            $days_diff_keys[$diff_days] = ["next_date"=>$next_date,"day"=>$event_avalibilty->day];
        }

         $start_date =  $days_diff_keys[min(array_keys($days_diff_keys))]["next_date"];
        // $start_date = $days_diff_keys[$less_key]["next_date"];
         return $start_date;

    }

}
