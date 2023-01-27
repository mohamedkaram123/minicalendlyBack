<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EventConfirmedCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $date = new Carbon($this->event_date);
        $from_time = $date->format('g:ia');

        $day = $date->format('l');
        $month = $date->format('M');
        $day_num = $date->day;
        $day_year = $date->year;
        $date_to = $this->duration_type == "m"? $date->addMinutes($this->duration):$date->addHours($this->duration);
        $to_time = $date_to->format('g:ia');

        return [
            "name"=>$this->event->name,
            "event_date"=>"$from_time - $to_time,$day,$month,$day_num,$day_year",
            "time_zone"=>"Africa/Cairo",
            "host_name"=>$this->event->user->name


        ];
    }
}
