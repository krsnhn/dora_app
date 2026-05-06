<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Destination;
use App\Models\TourPackage;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    /**
     * Test traveler can access traveler routes
     */
    public function test_traveler_can_access_traveler_dashboard()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $response = $this->actingAs($traveler)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test traveler cannot access agency routes
     */
    public function test_traveler_cannot_access_agency_dashboard()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $response = $this->actingAs($traveler)->get('/agency/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test traveler cannot access admin routes
     */
    public function test_traveler_cannot_access_admin_dashboard()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $response = $this->actingAs($traveler)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test agency can access agency routes
     */
    public function test_agency_can_access_agency_dashboard()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->get('/agency/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test unapproved agency can access agency dashboard
     */
    public function test_unapproved_agency_can_access_agency_dashboard()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'pending',
        ])->create();

        $response = $this->actingAs($agency)->get('/agency/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test unapproved agency cannot create tour package
     */
    public function test_unapproved_agency_cannot_create_tour_package()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'pending',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);

        $response = $this->actingAs($agency)->get('/agency/packages/create');

        $response->assertRedirect('/agency/dashboard');
        $response->assertSessionHas('error');
    }

    /**
     * Test approved agency can access agency dashboard
     */
    public function test_agency_cannot_access_admin_dashboard()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Test admin can access admin routes
     */
    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test admin can access user management
     */
    public function test_admin_can_access_user_management()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    /**
     * Test admin can verify agency
     */
    public function test_admin_can_verify_agency()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'pending',
        ])->create();

        $response = $this->actingAs($admin)->post("/admin/users/{$agency->id}/verify-agency", [
            'action' => 'approve',
            'notes' => 'Looks good!',
        ]);

        $this->assertEquals('approved', $agency->fresh()->agency_status);
    }

    /**
     * Test traveler cannot access favorites route
     */
    public function test_traveler_can_access_favorites()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $response = $this->actingAs($traveler)->get('/favorites');

        $response->assertStatus(200);
    }

    /**
     * Test guest cannot access protected routes
     */
    public function test_guest_cannot_access_protected_routes()
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/agency/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/favorites')->assertRedirect('/login');
    }

    /**
     * Test public routes are accessible to guests
     */
    public function test_public_routes_are_accessible_to_guests()
    {
        $this->get('/')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
        $this->get('/destinations')->assertStatus(200);
    }

    /**
     * Test traveler can access inquiries
     */
    public function test_traveler_can_access_inquiries()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $response = $this->actingAs($traveler)->get('/my-inquiries');

        $response->assertStatus(200);
    }

    /**
     * Test agency can access agency inquiries
     */
    public function test_agency_can_access_agency_inquiries()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->get('/agency/inquiries');

        $response->assertStatus(200);
    }
}
