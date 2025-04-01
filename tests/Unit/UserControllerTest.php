<?php
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

        // Act: Call the index route (note: JSON key is now "users")
        $response = $this->get('/api/users');

        // Assert: Response should be OK and contain 5 users
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'users');
    }

//    public function test_show_returns_single_user()
//    {
//        // Arrange: Create a user
//        $user = User::factory()->create();
//
//        // Act: Call the show route
//        $response = $this->get('/api/users/' . $user->id);
//
//        // Assert: Check response returns 200 and contains user data
//        $response->assertStatus(200);
//        $response->assertJsonFragment(['id' => $user->id, 'email' => $user->email]);
//    }

    public function test_store_creates_new_user()
    {
        // Arrange: Define user data for creation with all required fields
        $data = [
            'name'                  => 'Test User',
            'surname'               => 'Tester',
            'email'                 => 'test@example.com',
            'phone'                 => '1234567890',
            'country'               => 'Testland',
            'gender'                => 'male',
            'password'              => 'secret',
            'password_confirmation' => 'secret'
        ];

        // Act: Post data to the store route
        $response = $this->post('/api/users', $data);

        // Assert: New user is created
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_update_modifies_existing_user()
    {
        // Arrange: Create a user with required fields
        $user = User::factory()->create([
            'name'    => 'Old Name',
            'surname' => 'Tester',
            'email'   => 'old@example.com',
            'phone'   => '1234567890',
            'country' => 'Testland',
            'gender'  => 'male',
        ]);

        // Provide all required fields for update
        $data = [
            'name'     => 'Updated Name',
            'surname'  => 'Tester',
            'email'    => 'old@example.com',
            'phone'    => '1234567890',
            'country'  => 'Testland',
            'gender'   => 'male'
        ];

        // Act: Send a PUT request to update the user
        $response = $this->put('/api/users/' . $user->id, $data);

        // Assert: Check that the user's name was updated
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    public function test_destroy_deletes_user(): void
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Delete the user
        $response = $this->delete('/api/users/' . $user->id);

        // Assert: Confirm deletion with a 200 status code
        $response->assertStatus(200);
    }
}
