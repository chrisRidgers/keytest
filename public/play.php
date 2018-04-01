<?php

use KeyTest\App;
use KeyTest\KeyboardEventService;
use KeyTest\EventService;
use KeyTest\CliOutPutService;

require_once __DIR__.'/../vendor/autoload.php';

$app = new App(new KeyboardEventService, new EventService, new CliOutPutService);

$app->run();