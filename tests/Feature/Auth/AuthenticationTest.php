<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\CloudinaryService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class AuthenticationTest extends TestCase
{
    /**
     * Test user can view login page
     */
    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test user can view register page
     */
    public function test_user_can_view_register_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Test user can register as traveler
     */
    public function test_user_can_register_as_traveler()
    {
        $response = $this->post('/register', [
            'name' => 'Test Traveler',
            'email' => 'traveler@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'role' => 'traveler',
            'terms' => '1',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'traveler@example.com',
            'role' => 'traveler',
        ]);
    }

    /**
     * Test user can register as agency
     */
    public function test_user_can_register_as_agency()
    {
        // Mock Cloudinary so the file upload doesn't fail
        $this->mock(CloudinaryService::class, function ($mock) {
            $mock->shouldReceive('upload')->once()->andReturn('https://cloudinary.com/fake-id.jpg');
        });

        $response = $this->post('/register', [
            'name' => 'Test Agency',
            'email' => 'agency@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
            'role' => 'agency',
            'business_name' => 'Travel Agency Inc',
            'phone' => '1234567890',
            'address' => '123 Business St',
            'terms' => '1',
            'valid_id' => UploadedFile::fake()->image('id.jpg'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'agency@example.com',
            'role' => 'agency',
            'business_name' => 'Travel Agency Inc',
        ]);
    }

    /**
     * Test user cannot register with existing email
     */
    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post('/register', [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * Test user can login with correct credentials
     */
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test user cannot login with incorrect password
     */
    public function test_user_cannot_login_with_incorrect_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    /**
     * Test user can logout
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }

    /**
     * Test guest cannot access authenticated routes
     */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can access dashboard
     */
    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test password validation on registration
     */
    public function test_registration_password_validation()
    {
        // Weak password
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * Test passwords must match on registration
     */
    public function test_password_confirmation_validation()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password@123',
            'password_confirmation' => 'DifferentPassword@123',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
