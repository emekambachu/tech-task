<?php
// tests/Unit/EloquentUserRepositoryTest.php

namespace Tests\Unit;

use App\Infrastructure\Persistence\EloquentUserRepository;
use App\Domain\User\Entities\User as DomainUser;
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
        $name     = 'Alice';
        $surname  = 'Smith';
        $email    = 'alice@example.com';
        $phone    = '1234567890';
        $country  = 'USA';
        $gender   = 'female';
        $password = bcrypt('secret');

        // Create a DomainUser instance (id can be null for new user)
        $domainUser = new DomainUser(null, $name, $surname, $email, $phone, $country, $gender, $password, null, null);

        // Act: Create the user via the repository using save()
        $savedUser = $this->repository->save($domainUser);

        // Assert: Verify that a DomainUser was created and persisted in the DB
        $this->assertInstanceOf(DomainUser::class, $savedUser);
        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_find_user()
    {
        // Arrange: Create a user using a factory (this persists an Eloquent model)
        $modelUser = User::factory()->create();

        // Act: Retrieve the user using the repository (returns a DomainUser)
        $foundUser = $this->repository->find($modelUser->id);

        // Assert: Confirm the retrieved user is a DomainUser and has correct id
        $this->assertInstanceOf(DomainUser::class, $foundUser);
        $this->assertEquals($modelUser->id, $foundUser->getId());
    }

    public function test_update_user()
    {
        // Arrange: Create a user (persisted as an Eloquent model)
        $modelUser = User::factory()->create(['name' => 'Bob']);

        // Build a DomainUser instance from the model with updated data
        $updatedName = 'Robert';
        $domainUser = new DomainUser(
            $modelUser->id,
            $updatedName,
            $modelUser->surname,
            $modelUser->email,
            $modelUser->phone,
            $modelUser->country,
            $modelUser->gender,
            $modelUser->password,
            $modelUser->selfie,
            $modelUser->introduction
        );

        // Act: Update the user via the repository using the DomainUser
        $updatedUser = $this->repository->update($domainUser);

        // Assert: Ensure the update is reflected in the returned DomainUser and DB
        $this->assertEquals($updatedName, $updatedUser->getName());
        $this->assertDatabaseHas('users', ['id' => $modelUser->id, 'name' => $updatedName]);
    }

    public function test_delete_user()
    {
        // Arrange: Create a user using a factory
        $modelUser = User::factory()->create();

        // Build a DomainUser instance from the Eloquent model for deletion
        $domainUser = new DomainUser(
            $modelUser->id,
            $modelUser->name,
            $modelUser->surname,
            $modelUser->email,
            $modelUser->phone,
            $modelUser->country,
            $modelUser->gender,
            $modelUser->password,
            $modelUser->selfie,
            $modelUser->introduction
        );

        // Act: Delete the user via the repository
        $result = $this->repository->delete($domainUser);

        // Assert: Check that deletion returns true and the user is removed from the DB
        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $modelUser->id]);
    }
}
