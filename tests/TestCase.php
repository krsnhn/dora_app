<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * The current database connection to use during testing.
     *
     * @var string
     */
    protected $defaultConnection = 'testing';

    /**
     * Authenticate a user for the test.
     */
    protected function actingAsUser($user = null)
    {
        $user = $user ?? \App\Models\User::factory()->create();
        return $this->actingAs($user);
    }

    /**
     * Authenticate as a traveler.
     */
    protected function actingAsTraveler($user = null)
    {
        $user = $user ?? \App\Models\User::factory()->state(['role' => 'traveler'])->create();
        return $this->actingAs($user);
    }

    /**
     * Authenticate as an agency.
     */
    protected function actingAsAgency($user = null)
    {
        $user = $user ?? \App\Models\User::factory()->state(['role' => 'agency', 'agency_status' => 'approved'])->create();
        return $this->actingAs($user);
    }

    /**
     * Authenticate as an admin.
     */
    protected function actingAsAdmin($user = null)
    {
        $user = $user ?? \App\Models\User::factory()->state(['role' => 'admin'])->create();
        return $this->actingAs($user);
    }
}
