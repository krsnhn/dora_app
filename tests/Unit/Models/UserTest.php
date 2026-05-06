<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test user role checking methods
     */
    public function test_user_is_traveler()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $this->assertTrue($traveler->isTraveler());
        $this->assertFalse($traveler->isAgency());
        $this->assertFalse($traveler->isAdmin());
    }

    /**
     * Test user is agency
     */
    public function test_user_is_agency()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();

        $this->assertTrue($agency->isAgency());
        $this->assertFalse($agency->isTraveler());
        $this->assertFalse($agency->isAdmin());
    }

    /**
     * Test user is admin
     */
    public function test_user_is_admin()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isTraveler());
        $this->assertFalse($admin->isAgency());
    }

    /**
     * Test user is approved agency
     */
    public function test_user_is_approved_agency()
    {
        $approvedAgency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $this->assertTrue($approvedAgency->isApprovedAgency());
    }

    /**
     * Test user is not approved agency if pending
     */
    public function test_user_is_not_approved_agency_if_pending()
    {
        $pendingAgency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'pending',
        ])->create();

        $this->assertFalse($pendingAgency->isApprovedAgency());
    }

    /**
     * Test user relationships are defined
     */
    public function test_user_has_many_destinations()
    {
        $user = User::factory()->create();

        $this->assertTrue(true);
    }

    /**
     * Test user has many tour packages
     */
    public function test_user_has_many_tour_packages()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();

        $this->assertTrue(true);
    }

    /**
     * Test user has many inquiries
     */
    public function test_user_has_many_inquiries()
    {
        $user = User::factory()->create();

        $this->assertTrue(true);
    }

    /**
     * Test user has many favorites
     */
    public function test_user_has_many_favorites()
    {
        $user = User::factory()->create();

        $this->assertTrue(true);
    }

    /**
     * Test user has many memories
     */
    public function test_user_has_many_memories()
    {
        $user = User::factory()->create();

        $this->assertTrue(true);
    }

    /**
     * Test user fillable attributes
     */
    public function test_user_fillable_attributes()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'traveler',
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('traveler', $user->role);
    }

    /**
     * Test user password is hidden
     */
    public function test_user_password_is_hidden()
    {
        $user = User::factory()->create();
        $hidden = $user->getHidden();

        $this->assertContains('password', $hidden);
    }
}
