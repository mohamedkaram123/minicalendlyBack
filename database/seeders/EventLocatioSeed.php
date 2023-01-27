<?php

namespace Database\Seeders;

use App\Models\EventLocations;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventLocatioSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $location = new EventLocations();
        $location->title = "Zoom";
        $location->key = "zoom";
        $location->save();


        $location = new EventLocations();
        $location->title = "Google Meet";
        $location->key = "google_meet";
        $location->save();
    }
}
