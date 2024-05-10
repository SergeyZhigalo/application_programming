<?php

use Carbon\Carbon;

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

    public function getGroupName(): string
    {
        return explode(' - ', $this->summary)[0];
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function toArray(string $groupId, string $teacherId, string $universityId): array
    {
        return [
            'class_start' => Carbon::parse($this->DTStart)->format('Y-m-d H:i:s.u'),
            'class_end' => Carbon::parse($this->DTEnd)->format('Y-m-d H:i:s.u'),
            'place' => $this->location,
            'university_id' => $universityId,
            'group_id' => $groupId,
            'teacher_id' => $teacherId,
            'subject' => explode(' - ', $this->summary)[1],
            'uid' => $this->uid,
        ];
    }
}
