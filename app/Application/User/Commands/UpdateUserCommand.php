<?php

namespace App\Application\User\Commands;

class UpdateUserCommand
{
    public int $id;
    public string $name;
    public string $surname;
    public string $email;
    public string $phone;
    public string $country;
    public string $gender;
    public ?string $password;
    public ?string $selfie;
    public ?string $introduction;

    public function __construct(
        int $id,
        string $name,
        string $surname,
        string $email,
        string $phone,
        string $country,
        string $gender,
        ?string $password = null,
        ?string $selfie = null,
        ?string $introduction = null
    ) {
        $this->id           = $id;
        $this->name         = $name;
        $this->surname      = $surname;
        $this->email        = $email;
        $this->phone        = $phone;
        $this->country      = $country;
        $this->gender       = $gender;
        $this->password     = $password;
        $this->selfie       = $selfie;
        $this->introduction = $introduction;
    }
}
