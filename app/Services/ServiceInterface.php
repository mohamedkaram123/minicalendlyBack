<?php

namespace App\Services;

use App\Models\EventConfirm;

interface ServiceInterface{
    public function create(EventConfirm $data);
}
