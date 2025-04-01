<?php
// tests/Unit/EloquentUserRepositoryTest.php

namespace Tests\Unit;

use App\Infrastructure\Persistence\EloquentUserRepository;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EloquentUserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentUserRepository();
    }

    public function test_create_user()
    {
        // Arrange: Define data for a new user
        $data = [
            'name'     => 'Alice',
            'email'    => 'alice@example.com',
            'password' => bcrypt('secret'),
        ];

        // Act: Create the user via the repository
        $user = $this->repository->create($data);

        // Assert: Verify that a User was created
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'alice@example.com']);
    }

    public function test_find_user()
    {
        // Arrange: Create a user using a factory
        $user = User::factory()->create();

        // Act: Retrieve the user using the repository
        $foundUser = $this->repository->find($user->id);

        // Assert: Confirm the retrieved user is correct
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_update_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create(['name' => 'Bob']);

        // Act: Update the user's name via the repository
        $updateData = ['name' => 'Robert'];
        $updatedUser = $this->repository->update($user->id, $updateData);

        // Assert: Ensure the update is reflected in the model and database
        $this->assertEquals('Robert', $updatedUser->name);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Robert']);
    }

    public function test_delete_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Delete the user via the repository
        $result = $this->repository->delete($user->id);

        // Assert: Check that deletion returns true and the user is removed
        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
