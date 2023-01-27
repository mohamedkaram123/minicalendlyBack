<?php


namespace App\Services;



class ServiceFactory
{
    private static array $gateways = [
        'zoom' => ZoomMeeting::class,
        'google_meet' => GoogleCalenderMeet::class,

    ];


    public static function create(string $type,array $args = [])
    {
        return new self::$gateways[$type]($args);
    }


}
