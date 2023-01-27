<?php

namespace App\Services;

use App\Models\EventConfirm;
use Carbon\Carbon;
use DateTimeZone;
use GuzzleHttp\Client;
use Log;
use Firebase\JWT\JWT;
use Google_Client;

use Spatie\GoogleCalendar\Event;
use Google_Service_Calendar_Event;
use Google_Service_Calendar;
use Spatie\GoogleCalendar\GoogleCalendar;

/**
 * trait ZoomMeetingTrait
 */
class ZoomMeeting implements ServiceInterface
{
    public $client;
    public $jwt;
    public $headers;
    public $calenderGoogle;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer '.$this->jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];

        $this->calenderGoogle = new GoogleCalenderMeet();
    }
    public function generateZoomToken()
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    private function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL', '');
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime, new DateTimeZone('Africa/Cairo'));

            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function create(EventConfirm $data)
    {
        $path = 'users/me/meetings';
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $data->event->name,
                'type'       => 2,
                'start_time' => $this->toZoomTimeFormat($data->event_date),
                'duration'   => $data->duration,
                'agenda'     => $data->event->disc,
                'timezone'     => 'Africa/Cairo',
                'settings'   => [
                    'host_video'        => true,
                    'participant_video' => true,
                    'waiting_room'      => true
                ],
            ]),
        ];

        $response =  $this->client->post($url.$path, $body);
        $res_json = json_decode($response->getBody(), true);
        $this->calenderGoogle->create_event_zoom($data,$res_json);

        return $res_json;
    }





    // public function update($id, $data)
    // {
    //     $path = 'meetings/'.$id;
    //     $url = $this->retrieveZoomUrl();

    //     $body = [
    //         'headers' => $this->headers,
    //         'body'    => json_encode([
    //             'topic'      => $data['topic'],
    //             'type'       => 2,
    //             'start_time' => $this->toZoomTimeFormat($data['start_time']),
    //             'duration'   => $data['duration'],
    //             'agenda'     => (! empty($data['agenda'])) ? $data['agenda'] : null,
    //             'timezone'     => 'Asia/Kolkata',
    //             'settings'   => [
    //                 'host_video'        => ($data['host_video'] == "1") ? true : false,
    //                 'participant_video' => ($data['participant_video'] == "1") ? true : false,
    //                 'waiting_room'      => true,
    //             ],
    //         ]),
    //     ];
    //     $response =  $this->client->patch($url.$path, $body);

    //     return [
    //         'success' => $response->getStatusCode() === 204,
    //         'data'    => json_decode($response->getBody(), true),
    //     ];
    // }

    // public function get($id)
    // {
    //     $path = 'meetings/'.$id;
    //     $url = $this->retrieveZoomUrl();
    //     $this->jwt = $this->generateZoomToken();
    //     $body = [
    //         'headers' => $this->headers,
    //         'body'    => json_encode([]),
    //     ];

    //     $response =  $this->client->get($url.$path, $body);

    //     return [
    //         'success' => $response->getStatusCode() === 204,
    //         'data'    => json_decode($response->getBody(), true),
    //     ];
    // }

    // /**
    //  * @param string $id
    //  *
    //  * @return bool[]
    //  */
    // public function delete($id)
    // {
    //     $path = 'meetings/'.$id;
    //     $url = $this->retrieveZoomUrl();
    //     $body = [
    //         'headers' => $this->headers,
    //         'body'    => json_encode([]),
    //     ];

    //     $response =  $this->client->delete($url.$path, $body);

    //     return [
    //         'success' => $response->getStatusCode() === 204,
    //     ];
    // }
}
