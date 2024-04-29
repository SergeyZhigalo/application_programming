<?php

require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__.'/boot.php';

use IcsReader\IcsReader;

$file = file_get_contents(__DIR__.'/schedule/export_202404300026.ics');

$reader = new IcsReader();
$ics = $reader->parse($file);

dd(collect($ics->getCalendar()["VEVENT"])->first());

pdo();
