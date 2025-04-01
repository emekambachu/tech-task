<?php

namespace App\Application\User\Commands;

class CreateUserCommand
{
    public string $name;
    public string $surname;
    public string $email;
    public string $phone;
    public string $country;
    public string $gender;
    public string $password;
    public ?string $selfie;
    public ?string $introduction;

    public function __construct(
        string $name,
        string $surname,
        string $email,
        string $phone,
        string $country,
        string $gender,
        string $password,
        ?string $selfie = null,
        ?string $introduction = null
    ) {
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
