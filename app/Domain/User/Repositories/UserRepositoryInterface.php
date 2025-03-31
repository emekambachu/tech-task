<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;
    public function findAll(): array;
    public function save(User $user): User;
    public function update(User $user): User;
    public function delete(User $user): bool;
}
