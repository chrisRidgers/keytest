<?php
namespace KeyTest;

class KeyboardEventService
{

    private $callBacks = null;
    private $eventStore = null;
    private $app = null;
    private $keysToCheckRelease = null;

    public function __construct()
    {
        $this->keysToCheckRelease = [];
        
        $this->callBacks = [
            [
                $this,
                'listenForKeyPress'
            ],
            [
                $this,
                'checkForKeyRelease'
            ]
        ];
    }

    public function register(App $app)
    {
        $this->app = $app;

        $this->setupListeners($app);

        $this->eventStore = $app->getEventService();
    }

    public function setupListeners(App $app)
    {
        array_walk($this->callBacks, function ($callback) use ($app) {
            $app->addCallback($callback);
        });
    }

    public function receive($event)
    {
        $this->handleKeyReleasedEvent($event);
        $this->handleKeyPressEvent($event);
    }

    private function handleKeyReleasedEvent($event)
    {
        if (!is_a($event, KeyReleasedEvent::class)) {
            return;
        }

        unset($this->keysToCheckRelease[$event->getCode()]);
    }

    private function handleKeyPressEvent($event)
    {
        if (!is_a($event, KeyPressEvent::class)) {
            return;
        }

        $this->keysToCheckRelease[$event->getCode()] = $event;
    }

    public function checkForKeyRelease()
    {
        array_walk($this->keysToCheckRelease, function ($keyToCheckRelease) {
            $code = $keyToCheckRelease->getCode();

            $r = [STDIN];
            $w = null;
            $e = null;
    
            $streams = stream_select(
                $r,
                $w,
                $e,
                0,
                50000
            );

            if (!$streams) {
                $this->eventStore->publishEvent(new KeyReleasedEvent($code));

                return;
            }
    
            $c = ord(stream_get_contents(STDIN, 1));
    
            if ($c === $code) {
                return;
            }
    
            $this->eventStore->publishEvent(new KeyReleasedEvent($code));
        });
 
    }

    public function listenForKeyPress()
    {
        $r = [STDIN];
        $w = null;
        $e = null;

        $streams = stream_select(
            $r,
            $w,
            $e,
            0,
            200000
        );

        if ($streams) {
            $c = ord(stream_get_contents(STDIN, 1));

            $this->eventStore->publishEvent(new KeyPressEvent($c));
        }
    }
}