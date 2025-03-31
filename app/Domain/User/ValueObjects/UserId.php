<?php

namespace App\Domain\User\ValueObjects;

class UserId
{
    private int $value;

    public function __construct(int $value)
    {
        // Optionally add validation for the identifier.
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
