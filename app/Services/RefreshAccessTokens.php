<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Google_Client;
use Spatie\GoogleCalendar\Event;
use Google_Service_Calendar_Event;
use Google_Service_Calendar;

class RefreshAccessTokens{

    protected $client;

    function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
        $client->addScope(\Google_Service_Calendar::CALENDAR);
        $this->client = $client;
    }

    public function setAccessTokenExpired(User $user)
    {
        $res_google = json_decode($user->google_res,true);
        if($this->check_expire_date($user)){
            $this->client->refreshToken($res_google["refresh_token"]);
            $res_google= $this->client->getAccessToken();
            $res = $this->make_expire_date($res_google);
            $user->google_res = json_encode($res);
            $user->save();
        }

        return $user->google_res;
    }

    public function check_expire_date(User $user)
    {
        $res_google = json_decode($user->google_res,true);


        $check = false;
        if ((new Carbon($res_google['expire_date']))->setTimezone("Africa/Cairo") < (new Carbon(now()))) {
            $check =  true;
        }

        return $check;
    }

    public function make_expire_date($res)
{
    $res["expire_date"] = (new Carbon(now()))->addSeconds($res["expires_in"]);
    return $res;
}


}
