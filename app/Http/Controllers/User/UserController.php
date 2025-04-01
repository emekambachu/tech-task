<?php

namespace App\Http\Controllers\User;

use App\Domain\User\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Application\User\Services\UserService;
use App\Application\User\Commands\CreateUserCommand;
use App\Application\User\Commands\UpdateUserCommand;
use App\Application\User\Commands\DeleteUserCommand;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->listUsers();
            return response()->json([
                'success' => true,
                'users' => UserResource::collection($users),
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return $this->userService->responseError("Error Getting Users");
        }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('selfie')) {
                $path = $request->file('selfie')->store('selfies', 'public');
                $data['selfie'] = $path;
            }

            // Hash the password.
            $data['password'] = Hash::make($data['password']);

            $command = new CreateUserCommand(
                $data['name'],
                $data['surname'],
                $data['email'],
                $data['phone'],
                $data['country'],
                $data['gender'],
                $data['password'],
                $data['selfie'] ?? null,
                $data['introduction'] ?? null
            );

            $user = $this->userService->createUser($command);
            return response()->json([
                'success' => true,
                'user' => new UserResource($user),
            ], 201);

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->userService->responseError("Error creating user");
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();

        try {

            if ($request->hasFile('selfie')) {
                $path = $request->file('selfie')->store('selfies', 'public');
                $data['selfie'] = $path;
            }

            // Hash password if provided; otherwise leave it null.
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                $data['password'] = null;
            }

            $command = new UpdateUserCommand(
                $id,
                $data['name'],
                $data['surname'],
                $data['email'],
                $data['phone'],
                $data['country'],
                $data['gender'],
                $data['password'],
                $data['selfie'] ?? null,
                $data['introduction'] ?? null
            );

            $user = $this->userService->updateUser($command);
            return response()->json([
                'success' => true,
                'user' => new UserResource($user),
            ]);

        }catch(\Exception $e){
            Log::error($e->getMessage());
            return $this->userService->responseError("Error updating user");
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        try{

            $command = new DeleteUserCommand($id);
            $this->userService->deleteUser($command);
            return response()->json(null, 204);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return $this->userService->responseError("Error deleting user");
        }
    }
}
