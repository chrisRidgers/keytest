<?php
namespace KeyTest;

class KeyReleasedEvent
{
    private $keyCode;

    public function __construct($keyCode)
    {
        $this->keyCode = $keyCode;
    }

    public function __toString()
    {
        return "Keyboard Event, KeyReleased: $this->keyCode\n";
    }

    public function getCode()
    {
        return $this->keyCode;
    }
}