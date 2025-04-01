<?php
// tests/Unit/UserServiceTest.php

namespace Tests\Unit;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Domain\User\Entities\User as DomainUser;
use Tests\TestCase;
use App\Application\User\Services\UserService;
use App\Application\User\Commands\CreateUserCommand;
use App\Application\User\Commands\UpdateUserCommand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        // Use the actual repository to persist data.
        $this->userRepository = new EloquentUserRepository();
        $this->userService = new UserService($this->userRepository);
    }

    public function test_create_user()
    {
        $name     = 'John';
        $surname  = 'Doe';
        $email    = 'john@example.com';
        $phone    = '1234567890';
        $country  = 'USA';
        $gender   = 'male';
        $password = bcrypt('secret');

        $command = new CreateUserCommand($name, $surname, $email, $phone, $country, $gender, $password);

        $user = $this->userService->createUser($command);

        $this->assertInstanceOf(DomainUser::class, $user);
        // This assertion now checks that a record exists in the DB.
        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_update_user()
    {
        $modelUser = User::factory()->create([
            'name'     => 'Jane',
            'surname'  => 'Doe',
            'email'    => 'jane@example.com',
            'phone'    => '0987654321',
            'country'  => 'USA',
            'gender'   => 'female',
        ]);

        $updatedName = 'Jane Smith';
        $surname     = $modelUser->surname;
        $email       = $modelUser->email;
        $phone       = $modelUser->phone;
        $country     = $modelUser->country;
        $gender      = $modelUser->gender;

        $command = new UpdateUserCommand($modelUser->id, $updatedName, $surname, $email, $phone, $country, $gender);

        $user = $this->userService->updateUser($command);

        $this->assertEquals($updatedName, $user->getName());
        $this->assertDatabaseHas('users', ['id' => $modelUser->id, 'name' => $updatedName]);
    }
}
