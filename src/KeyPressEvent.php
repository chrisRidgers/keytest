<?php
namespace KeyTest;

class KeyPressEvent
{
    private $keyCode;

    public function __construct($keyCode)
    {
        $this->keyCode = $keyCode;
    }

    public function __toString()
    {
        return "Keyboard Event, KeyPressed: $this->keyCode\n";
    }

    public function getCode()
    {
        return $this->keyCode;
    }
}