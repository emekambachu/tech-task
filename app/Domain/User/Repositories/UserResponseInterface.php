<?php

namespace App\Domain\User\Repositories;

use Illuminate\Http\JsonResponse;

interface UserResponseInterface
{
    public function responseError(string $message): jsonResponse;
}
