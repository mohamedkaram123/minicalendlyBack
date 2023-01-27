<?php

namespace Modules\Events\Http\Controllers;

use App\Http\Resources\EventCollection;
use App\Http\Resources\EventConfirmedCollection;
use App\Http\Resources\LocationCollection;
use App\Models\Event;
use App\Models\EventServiceConfirm;
use App\Models\User;
use App\Services\ServiceContext;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Events\Http\Actions\EventAction;
use Modules\Events\Http\Actions\EventAvalibiltiesAction;
use Modules\Events\Http\Actions\EventConfirmAction;
use Modules\Events\Http\Actions\EventLocationAction;
use Modules\Events\Http\Actions\EventServiceAction;

class EventsController extends Controller
{
    protected $event_action;
    protected $event_avalibilty_action;
    protected $event_location;
    protected $event_confirm_action;
    protected $event_service_action;

    protected $service_meeting;

 public  function __construct()
  {
      $this->event_action = new EventAction();
      $this->event_avalibilty_action = new EventAvalibiltiesAction();
      $this->event_location = new EventLocationAction();
      $this->event_confirm_action = new EventConfirmAction();
      $this->event_service_action = new EventServiceAction();

      $this->service_meeting = new ServiceContext();

  }

  public function validate_event_store(Request $req)
  {
    $validate = Validator::make($req->all(),[
        "location_id"=>"required",
        "name"=>"required|string",
        "disc"=>"nullable|string",
        "link"=>"required|string"
      ],[
        "location_id.required"=>"please enter location is required",
        "name.required"=>"please enter event name is required",
        "disc.string"=>"please enter disc as string",
      ]);

      return $validate;
  }

  public function store(Request $req)
  {
      $validate = $this->validate_event_store($req);

      if($validate->fails()){
        return  fail("error",$validate->errors(),301);
      }

      $event  = $this->event_action->store_event($req);
      $this->event_avalibilty_action->make_minicalendly_availibilities($event);
      $event_model = Event::find($event->id);
      return success("success",new EventCollection($event_model));
  }


  public function get_event(Request $req)
  {
    $event = $this->event_action->get_event($req);
    return success("success",new EventCollection($event));

  }



  public function validate_event_update(Request $req)
  {
    $validate = Validator::make($req->all(),[
        "duration"=>"integer|required",
        "duration_type"=>"required|in:m,h",
        "date_range"=>"required",
        "date_range_custom"=>"required|in:inf,cus",

      ],[
        "duration.required"=>"please enter duration",
        "duration_type.required"=>"please enter duration type",
        "date_range_custom.required"=>"please enter date range custom",
        "duration_type.in"=>"please enter duration type m or h",
        "date_range_custom.in"=>"please enter date range custom or infinity",

      ]);

      return $validate;
  }


  public function validate_minicalendly_availibilities(Request $req)
  {
     $errors = [];

     foreach ($req->avalibility as $item) {
        foreach ($item["times"] as $num => $time) {
            foreach ($item as $key => $value) {
                if($value == ""){
                  $day = $item["day"];

                    $errors[$item][$num][$key] = trans("please put required item for day $day");
                }
            }
        }
     }

     return $errors;
  }


  public function update(Request $req)
  {

    $validate = $this->validate_event_update($req);

      if($validate->fails()){
       return  fail("error",$validate->errors()->first());
      }

      $errors = $this->validate_minicalendly_availibilities($req);

      if(count($errors) > 0){
        return $errors[0];
      }

      $event  = $this->event_action->update_event($req);
      $this->event_avalibilty_action->delete_minicalendly_availibilities($event);
      $days_avalibilty  = $this->event_avalibilty_action->store_minicalendly_availibilities($req);
      $data = ["event"=> new EventCollection($event)];

      return success("success",$data);

  }

  public function update_level1(Request $req)
  {
        $validate_level1 = $this->validate_event_store($req);

        if($validate_level1->fails()){
          return  fail("error",$validate_level1->errors());
        }

      $event  = $this->event_action->update_event_level1($req);
      $data = ["event"=> new EventCollection($event)];

      return success("success",$data);

  }


  public function get_all_locations()
  {
    $locations = $this->event_location->get_locations();
    return success("success",  LocationCollection::collection($locations) );

  }

public function events_user()
 {
    $events = $this->event_action->get_user_events();
    return success("success", EventCollection::collection($events));
 }

 public function get_all_times(Request $req)
 {
    $event = Event::find($req->id);
    $times = $event->avilibilties_days->where("day",$req->day)->first();
    if(empty($times)){
        return success("success", []);

    }
    $times_model  = [];
    foreach ($times->times as $time) {

        $time_between = $this->event_avalibilty_action->from_to_time($time->from_time,$time->to_time,$event->duration,$event->duration_type);
        $times_model[] = $time_between;
    }

   $times_model =  $this->event_confirm_action->check_confirmed_events($event,$times_model);

    return success("success", $times_model);
 }


 public function event_confirm_store(Request $req)
 {

    $validate =Validator::make($req->all(),[
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
    ]);
    if($validate->fails()){
        return fail("error",$validate->errors());
    }

    $guest =  $this->event_confirm_action->register_guest($req);
    $user = User::find($req->user_id);

    $event_confirm_action =  $this->event_confirm_action->store($req,$guest,$user);

    $res =  $this->service_meeting
                 ->set($event_confirm_action->location_key)
                 ->create($event_confirm_action);
    $res_encode = json_encode($res);
    $this->event_service_action->store_service_confirm($event_confirm_action,$res_encode);

    return success("success",new EventConfirmedCollection($event_confirm_action) );
 }
}
