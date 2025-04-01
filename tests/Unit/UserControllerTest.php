<?php
// tests/Feature/UserControllerTest.php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_users()
    {
        // Arrange: Create some users
        User::factory()->count(5)->create();

        // Act: Call the index route (adjust the URI as needed)
        $response = $this->get('/users');

        // Assert: Response should be OK and contain 5 users (adjust structure as necessary)
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_show_returns_single_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Call the show route
        $response = $this->get('/users/' . $user->id);

        // Assert: Check response
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $user->id, 'email' => $user->email]);
    }

    public function test_store_creates_new_user()
    {
        // Arrange: Define user data for creation
        $data = [
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'secret', // assuming your controller hashes the password
        ];

        // Act: Post data to the store route
        $response = $this->post('/users', $data);

        // Assert: New user is created (adjust response status/code as necessary)
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_update_modifies_existing_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create([
            'name' => 'Old Name',
        ]);

        $data = ['name' => 'Updated Name'];

        // Act: Send a PUT request to update the user
        $response = $this->put('/users/' . $user->id, $data);

        // Assert: Check that the user's name was updated
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_destroy_deletes_user()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Delete the user
        $response = $this->delete('/users/' . $user->id);

        // Assert: Confirm deletion (status code might vary based on your implementation)
        $response->assertStatus(204);
        $this->assertDeleted($user);
    }
}
