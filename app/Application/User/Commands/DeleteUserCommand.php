<?php

namespace App\Application\User\Commands;

class DeleteUserCommand
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
