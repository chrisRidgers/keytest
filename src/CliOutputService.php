<?php
namespace KeyTest;

class CliOutPutService
{
    public function register(App $app)
    {
    }

    public function receive($event)
    {
        echo (string)$event;
    }
}