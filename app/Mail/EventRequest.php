<?php

namespace App\Mail;

use Illuminate\Support\Collection;

class EventRequest{

    protected string $startLink;
    protected string $joinLink;
    protected string $start_date;
    protected string $end_date;
    protected string $location;
    protected string $emailHost;
    protected string $emailGuest;
    protected string $timeZone;
    protected int $duration;
    protected string $duration_type;
    protected string $password;
    protected string $host_name;
    protected string $guest_name;
    protected string $mail_type;
    protected string $title;
    protected string $time_from;
    protected string $time_to;



    public function setJoinLink(string $joinLink): self
    {
        $this->joinLink = $joinLink;
        return $this;
    }


    public function setStartLink(string $startLink): self
    {
        $this->startLink = $startLink;
        return $this;
    }

    public function setStartDate(string $start_date): self
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function setEndDate(string $end_date): self
    {
        $this->end_date = $end_date;
        return $this;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }


    public function setEmailHost(string $emailHost): self
    {
        $this->emailHost = $emailHost;
        return $this;
    }


    public function setEmailGuest(string $emailGuest): self
    {
        $this->emailGuest = $emailGuest;
        return $this;
    }


    public function setTimeZone(string $timeZone): self
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function setHostName(string $host_name): self
    {
        $this->host_name = $host_name;
        return $this;
    }


    public function setGuestName(string $guest_name): self
    {
        $this->guest_name = $guest_name;
        return $this;
    }


    public function setDurationType(string $duration_type): self
    {
        $this->duration_type = $duration_type;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }


    public function setMailType(string $mail_type): self
    {
        $this->mail_type = $mail_type;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setTimeFrom(string $time_from): self
    {
        $this->time_from = $time_from;
        return $this;
    }

    public function setTimeTo(string $time_to): self
    {
        $this->time_to = $time_to;
        return $this;
    }

    public function toArray():array
    {
        $arr = [
         "startLink"=>$this->startLink??"",
         "joinLink"=>$this->joinLink??"",
         "start_date"=>$this->start_date??"",
         "end_date"=>$this->end_date??"",
         "location"=>$this->location??"",
         "emailHost"=>$this->emailHost??"",
         "emailGuest"=>$this->emailGuest??"",
         "timeZone"=>$this->timeZone??"Africa/Cairo",
         "duration"=>$this->duration??0,
         "duration_type"=>$this->duration_type??"m",
         "password"=>$this->password??"",
         "mail_type"=>$this->mail_type??"guest",
         "host_name"=>$this->host_name??"",
         "guest_name"=>$this->guest_name??"",
         "title"=>$this->title??"",
         "time_from"=>$this->time_from??"",
         "time_to"=>$this->time_to??"",
         "app_link"=>env("APP_REACT_LINK")??"",



        ];


        return $arr;
    }
}
