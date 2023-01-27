<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Events\Http\Actions\EventAvalibiltiesAction;

class EventCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user_name = auth("api")->user()?auth("api")->user()->name:"";
        return [
            "id"=>$this->id,
            "location"=>$this->location,
            "avalibility"=>EventAvalibiltiesCollection::collection($this->avilibilties_days) ,
            "days"=>$this->avilibilties_days->map(function($item){
                return $item->day;
            }),
            "name"=>$this->name,
            "user"=>new UserCollection($this->user),
            "disc"=>$this->disc,
            "link"=> $this->link,
            "duration_type"=>$this->duration_type,
            "duration"=>$this->duration,
            "date_range"=>$this->date_range,
            "active"=>$this->active,

            "date_range_custom"=>$this->date_range_custom,
            "options_times"=> (new EventAvalibiltiesAction)->from_to_time("00:00","23:45",15,"m"),
            "created_at"=>(new EventAvalibiltiesAction)->make_start_date($this),
        ];
    }
}
