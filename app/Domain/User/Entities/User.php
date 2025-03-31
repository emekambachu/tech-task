<?php

namespace App\Domain\User\Entities;

class User
{
    private ?int $id;
    private string $name;
    private string $surname;
    private string $email;
    private string $phone;
    private string $country;
    private string $gender;
    private string $password;
    private ?string $selfie;
    private ?string $introduction;

    public function __construct(
        ?int $id,
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

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getSurname(): string
    {
        return $this->surname;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getCountry(): string
    {
        return $this->country;
    }
    public function getGender(): string
    {
        return $this->gender;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getSelfie(): ?string
    {
        return $this->selfie;
    }
    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }
}
