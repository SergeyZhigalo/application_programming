<?php

require_once __DIR__ . '/../boot.php';
require_once __DIR__ . '/../DTO/ScheduleDTO.php';

use IcsReader\IcsReader;

$target_dir = "../schedule/";
$target_file = $target_dir . basename($_FILES["file"]["name"][0]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

if ($fileType !== "ics") {
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"][0], $target_file)) {
        echo "The file ". basename( $_FILES["file"]["name"][0]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$file = file_get_contents($target_file);

$reader = new IcsReader();
$ics = $reader->parse($file);

foreach ($ics->getEvents() as $event) {
    dd(new ScheduleDTO($event));
}
