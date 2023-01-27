<?php

namespace App\Console\Commands;

use App\Mail\SendEventMail;
use App\Models\Event;
use App\Models\EventConfirm;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mail;

class SendEventNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendEventNotify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events_confirms= EventConfirm::where("status","pending")->get();
        foreach($events_confirms as $events_confirm){
            if(Carbon::parse($events_confirm->event_date)->subHour() < Carbon::parse(now()) ){
                Mail::to($events_confirm->host->email)->send(new SendEventMail($events_confirm,"host"));
                Mail::to($events_confirm->guest->email)->send(new SendEventMail($events_confirm,"guest"));
                $events_confirm->status = "complete";
                $events_confirm->save();

                echo "send email";

            }

        }
        echo "done";
    }
}
