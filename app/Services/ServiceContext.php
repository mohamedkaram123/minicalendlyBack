<?php


namespace App\Services;

use App\Models\EventConfirm;

class ServiceContext
{

    public ServiceInterface $service;

    public function set(string $service_key)
    {
        $this->service =  ServiceFactory::create($service_key);
        return $this;
    }

    public function create(EventConfirm $eventConfirm)
    {
       return $this->service->create($eventConfirm);
    }

}
