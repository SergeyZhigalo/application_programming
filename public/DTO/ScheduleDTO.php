<?php

class ScheduleDTO
{
    private ?string $description;
    private ?string $DTEnd;
    private ?string $DTStamp;
    private ?string $DTStart;
    private ?string $location;
    private ?int $sequence;
    private ?string $summary;
    private ?string $uid;

    public function __construct($data)
    {
        array_key_exists('DESCRIPTION', $data) ? $this->description = $data['DESCRIPTION'] : $this->description = null;
        array_key_exists('DTEND', $data) ? $this->DTEnd = $data['DTEND'] : $this->DTEnd = null;
        array_key_exists('DTSTAMP', $data) ? $this->DTStamp = $data['DTSTAMP'] : $this->DTStamp = null;
        array_key_exists('DTSTART', $data) ? $this->DTStart = $data['DTSTART'] : $this->DTStart = null;
        array_key_exists('LOCATION', $data) ? $this->location = $data['LOCATION'] : $this->location = null;
        array_key_exists('SEQUENCE', $data) ? $this->sequence = $data['SEQUENCE'] : $this->sequence = null;
        array_key_exists('SUMMARY', $data) ? $this->summary = $data['SUMMARY'] : $this->summary = null;
        array_key_exists('UID', $data) ? $this->uid = $data['UID'] : $this->uid = null;
    }
}
