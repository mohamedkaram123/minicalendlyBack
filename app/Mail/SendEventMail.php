<?php

namespace App\Mail;

use App\Models\EventConfirm;
use App\Models\EventServiceConfirm;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public EventConfirm $event_confirm;
    public  $eventReq;
    public  $service;
    public string $type;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventConfirm $event_confirm,$type)
    {
        //
        $this->type = $type;
        $this->event_confirm = $event_confirm;
        $this->eventReq = new EventRequest;

        $this->service = $event_confirm->get_res();

        if($event_confirm->location_key == "zoom"){
            $data = $this->iniDataZoom();
        }else if($event_confirm->location_key == "google_meet"){
            $data = $this->iniDataGoogleMeet();
        }
        $this->data = $data;

    }

    public function iniDataZoom()
    {
        $end_date = add_duration(new Carbon($this->service["start_time"]),$this->event_confirm->duration,$this->event_confirm->duration_type)->setTimezone("Africa/Cairo");
        $start_date = (new Carbon($this->service["start_time"]))->setTimezone("Africa/Cairo");

        $eventReq =  $this->eventReq->setDuration($this->event_confirm->duration)
                                    ->setDurationType($this->event_confirm->duration_type)
                                    ->setEmailHost($this->event_confirm->host->email)
                                    ->setEmailGuest($this->event_confirm->guest->email)
                                    ->setHostName($this->event_confirm->host->name)
                                    ->setGuestName($this->event_confirm->guest->name)
                                    ->setTitle($this->event_confirm->event->name)
                                    ->setMailType($this->type)
                                    ->setEndDate($end_date->format('Y-m-d'))
                                    ->setStartDate($start_date->format('Y-m-d'))
                                    ->setTimeFrom($start_date->format("h:i a"))
                                    ->setTimeTo($end_date->format("h:i a"))
                                    ->setJoinLink($this->service["join_url"])
                                    ->setStartLink($this->service["start_url"])
                                    ->setPassword($this->service["password"])
                                    ->setLocation("This is a Zoom web conference.")
                                    ->toArray();
    return $eventReq;

    }

    public function iniDataGoogleMeet   ()
    {
        $end_date = (new Carbon($this->service["end"]["dateTime"]))->setTimezone("Africa/Cairo");//add_duration(new Carbon($this->service["start"]["dateTime"]),$this->event_confirm->duration,$this->event_confirm->duration_type);
        $start_date = (new Carbon($this->service["start"]["dateTime"]))->setTimezone("Africa/Cairo");
        $join_link = $this->service["conferenceData"]["entryPoints"][0]["uri"];
        $eventReq =  $this->eventReq->setDuration($this->event_confirm->duration)
                                    ->setDurationType($this->event_confirm->duration_type)
                                    ->setEmailHost($this->event_confirm->host->email)
                                    ->setEmailGuest($this->event_confirm->guest->email)
                                    ->setHostName($this->event_confirm->host->name)
                                    ->setGuestName($this->event_confirm->guest->name)
                                    ->setTitle($this->event_confirm->event->name)
                                    ->setMailType($this->type)
                                    ->setEndDate($end_date->format('Y-m-d'))
                                    ->setTimeFrom($start_date->format("h:ia"))
                                    ->setTimeTo($end_date->format("h:ia"))
                                    ->setStartDate($start_date->format('Y-m-d'))
                                    ->setJoinLink($join_link)
                                    ->setStartLink($join_link)
                                    ->setLocation("This is a Google Meet web conference.")
                                    ->toArray();
       return $eventReq;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Event Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mails.event_mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
