<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAvailability extends Model
{
    use HasFactory;



        public function getTimesAttribute($value)
    {
        return json_decode($value);
    }

}
