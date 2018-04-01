<?php

namespace KeyTest;

class EventService
{
    private $subscribedServices;

    public function register(App $app)
    {
    }

    public function subscribeService($service)
    {
        $this->subscribedServices[] = $service;
    }

    public function publishEvent($event)
    {
        array_walk($this->subscribedServices, function ($service) use ($event) {
            $service->receive($event);
        });
    }
}
