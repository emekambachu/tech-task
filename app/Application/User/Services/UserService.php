<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Application\User\Commands\CreateUserCommand;
use App\Application\User\Commands\UpdateUserCommand;
use App\Application\User\Commands\DeleteUserCommand;
use App\Domain\User\Repositories\UserResponseInterface;
use Illuminate\Http\JsonResponse;

class UserService implements UserResponseInterface
{
    private UserRepositoryInterface $userRepository;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(CreateUserCommand $command): User
    {
        $user = new User(
            null,
            $command->name,
            $command->surname,
            $command->email,
            $command->phone,
            $command->country,
            $command->gender,
            $command->password,
            $command->selfie,
            $command->introduction
        );
        return $this->userRepository->save($user);
    }

    /**
     * @throws UserNotFoundException
     */
    public function updateUser(UpdateUserCommand $command): User
    {
        $existingUser = $this->userRepository->find($command->id);
        if (!$existingUser) {
            throw new UserNotFoundException("User not found");
        }

        // If password is not provided, use the existing one.
        $password = $command->password ?? $existingUser->getPassword();

        $user = new User(
            $command->id,
            $command->name,
            $command->surname,
            $command->email,
            $command->phone,
            $command->country,
            $command->gender,
            $password,
            $command->selfie,
            $command->introduction
        );

        return $this->userRepository->update($user);
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteUser(DeleteUserCommand $command): bool
    {
        $user = $this->userRepository->find($command->id);
        if (!$user) {
            throw new UserNotFoundException("User not found");
        }
        return $this->userRepository->delete($user);
    }

    public function listUsers(): array
    {
        return $this->userRepository->findAll();
    }

    public function responseError(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ],500);
    }
}
