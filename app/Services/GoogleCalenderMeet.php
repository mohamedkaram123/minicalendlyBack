<?php
namespace App\Services;

use App\Models\EventConfirm;
use Carbon\Carbon;
use Google_Client;
use Spatie\GoogleCalendar\Event;
use Google_Service_Calendar_Event;
use Google_Service_Calendar;

class GoogleCalenderMeet implements ServiceInterface
{
    protected $data;

    public function init_service($data)
    {
        $google_res_access = json_decode($data->host->google_res);
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
        $client->addScope(\Google_Service_Calendar::CALENDAR);
        $refreshClassCheck = new RefreshAccessTokens();

        $check_access_token_expire = $refreshClassCheck->check_expire_date($data->host);
        if($check_access_token_expire){
            $res_google = json_decode($refreshClassCheck->setAccessTokenExpired($data->host),true);
            $client->setAccessToken($res_google["access_token"]);

        }else{
            $client->setAccessToken($google_res_access->access_token);

        }
        $service = new \Google_Service_Calendar($client);
        // $results = $service->events->listEvents($calendarId, $optParams);

        return $service;
    }

    public function create(EventConfirm $data)
    {

        $service = $this->init_service($data);
        $start_date = new Carbon($data->event_date);

        $end_date = add_duration($start_date->copy(),$data->duration,$data->duration_type);

        $event = new \Google_Service_Calendar_Event(array(
            'summary' => $data->event->name,
            'description' => $data->event->disc,
            'timeZone'=>'Africa/Cairo',
            'start' => array(
            'dateTime' => $start_date->toDateTimeLocalString(),
            'timeZone' => 'Africa/Cairo',
            ),
            'end' => array(
            'dateTime' => $end_date->toDateTimeLocalString(),
            'timeZone' => 'Africa/Cairo',
            ),

            'attendees' => array(
            array('email' => $data->guest->email),
            ),

            'conferenceData' => array(
                "createRequest"=>[
                    "requestId"=>"req" . time()
                ]
            ),
        ));


        $calendarId = "primary"; //$data->event->name . $data->event->id;
        $event = $service->events->insert($calendarId, $event,["conferenceDataVersion"=>1,"sendNotifications"=>true]);

        // $start_date = new Carbon($data->event_date);
        // $end_date = add_duration($start_date,$data->duration,$data->duration_type);

        // $event = new Event;
        // $event->name = $data->event->name;
        // $event->startDateTime = $start_date;
        // $event->endDateTime = $end_date;
        // $event->addAttendee([
        //     "email"=>$data->guest->email,
        //     "name"=>$data->guest->name,
        // ]);
        // $res = $event->save();

        return $event;
    }

    public function create_event_zoom(EventConfirm $data,$service_res)
    {
        $link_event = $service_res["join_url"];
        $password = $service_res["password"];

        $event_disc = $data->event->disc;
        $event_name = $data->event->name;
        $service = $this->init_service($data);
        $start_date = new Carbon($data->event_date);

        $end_date = add_duration($start_date->copy(),$data->duration,$data->duration_type);

        $event = new \Google_Service_Calendar_Event(array(
            'summary' => $data->event->name,
            'description' => "Event Name: $event_name \n
                           Location: This is a Zoom web conference. \n
                           You can join this meeting from your computer, tablet, or smartphone. \n
                          $link_event \n
                          $event_disc \n
                          password : $password",
            "location"=>"$link_event",
            'timeZone'=>'Africa/Cairo',
            'start' => array(
            'dateTime' => $start_date->toDateTimeLocalString(),
            'timeZone' => 'Africa/Cairo',
            ),
            'end' => array(
            'dateTime' => $end_date->toDateTimeLocalString(),
            'timeZone' => 'Africa/Cairo',
            ),

            'attendees' => array(
            array('email' => $data->guest->email),
            array('email' => $data->host->email),

            ),


        ));


        $calendarId = "primary"; //$data->event->name . $data->event->id;
        $event = $service->events->insert($calendarId, $event,["sendNotifications"=>true]);


        return $event;
    }
}
