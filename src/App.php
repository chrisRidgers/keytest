<?php
namespace KeyTest;

class App
{
    private $callbacks = [];

    public function __construct(KeyboardEventService $keyboardEventService, EventService $eventsService, CliOutputService $cliOutputService)
    {
        readline_callback_handler_install('', function () {
        });

        $this->eventService = $eventsService;

        $keyboardEventService->register($this);
        $eventsService->register($this);
        $cliOutputService->register($this);

        $eventsService->subscribeService($cliOutputService);
        $eventsService->subscribeService($keyboardEventService);
    }

    public function getEventService()
    {
        return $this->eventService;
    }

    public function addCallback($callback) 
    {
        $this->callbacks[hash('md5', serialize($callback))] = $callback;
    }

    public function receiveEvent($event)
    {
        $this->eventsService->publishEvent($event);
    }

    public function run()
    {
        while (true) {

            array_walk($this->callbacks, function ($callback) {
                call_user_func($callback);
            });
        }
    }
}