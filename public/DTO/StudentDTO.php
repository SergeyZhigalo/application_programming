<?php

use Carbon\Carbon;

class StudentDTO
{
    private ?string $fullName;
    private ?string $email;

    public function __construct($data)
    {
        array_key_exists('fullName', $data) ? $this->fullName = $data['fullName'] : $this->fullName = null;
        array_key_exists('email', $data) ? $this->email = $data['email'] : $this->email = null;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function toArray(string $groupId): array
    {
        return [
            'full_name' => $this->fullName,
            'email' => $this->email,
            'group_id' => $groupId,
            'group_head' => 0,
            'password' => password_hash(explode('@', $this->email)[0], PASSWORD_DEFAULT),
        ];
    }
}
