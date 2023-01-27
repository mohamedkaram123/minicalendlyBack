<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\EventLocations;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
