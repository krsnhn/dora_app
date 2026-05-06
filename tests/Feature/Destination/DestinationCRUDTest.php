<?php

namespace Tests\Feature\Destination;

use App\Models\User;
use App\Models\Destination;
use Tests\TestCase;

class DestinationCRUDTest extends TestCase
{
    /**
     * Test anyone can view destinations
     */
    public function test_guest_can_view_destinations()
    {
        $user = User::factory()->create();
        Destination::factory()->create([
            'created_by' => $user->id,
            'is_approved' => true,
        ]);

        $response = $this->get('/destinations');

        $response->assertStatus(200);
    }

    /**
     * Test can view destination detail
     */
    public function test_guest_can_view_destination_detail()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'is_approved' => true,
        ]);

        $response = $this->get("/destinations/{$destination->id}");

        $response->assertStatus(200);
    }

    /**
     * Test agency can create destination request
     */
    public function test_agency_can_create_destination_request()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->post('/agency/destinations', [
            'name' => 'New Destination',
            'country' => 'Philippines',
            'location' => 'Manila',
            'description' => 'A beautiful city destination in the Philippines',
            'tags' => 'city, urban, tourism',
            'latitude' => 14.5994,
            'longitude' => 120.9842,
        ]);

        $this->assertDatabaseHas('destination_requests', [
            'name' => 'New Destination',
            'agency_id' => $agency->id,
        ]);
    }

    /**
     * Test traveler cannot create destination
     */
    public function test_traveler_cannot_create_destination()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $response = $this->actingAs($traveler)->post('/agency/destinations', [
            'name' => 'Unauthorized Destination',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test destination requires mandatory fields
     */
    public function test_destination_creation_requires_mandatory_fields()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->post('/agency/destinations', [
            // Missing required fields
        ]);

        $response->assertSessionHasErrors(['name', 'country', 'location']);
    }

    /**
     * Test destination coordinates must be valid
     */
    public function test_destination_coordinates_validation()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->post('/agency/destinations', [
            'name' => 'Test Destination',
            'country' => 'Philippines',
            'location' => 'Test Location',
            'latitude' => 'invalid',
            'longitude' => 121.9272,
        ]);

        $response->assertSessionHasErrors('latitude');
    }

    /**
     * Test pending destination request is not approved by default
     */
    public function test_new_destination_is_not_approved()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->post('/agency/destinations', [
            'name' => 'Pending Destination',
            'country' => 'Philippines',
            'location' => 'Test Location',
            'description' => 'A pending destination request for testing purposes.',
        ]);

        $this->assertDatabaseHas('destination_requests', [
            'name' => 'Pending Destination',
            'agency_id' => $agency->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test admin can approve destination
     */
    public function test_admin_can_approve_destination()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($admin)->patch(
            "/admin/destinations/{$destination->id}/toggle"
        );

        $this->assertTrue($destination->fresh()->is_approved);
    }

    /**
     * Test destination tags can be queried
     */
    public function test_destination_tags_parsing()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'tags' => 'beach, island, tropical, resort',
        ]);

        $tags = $destination->tags_array;

        $this->assertContains('beach', $tags);
        $this->assertContains('island', $tags);
        $this->assertCount(4, $tags);
    }

    /**
     * Test destination is favorited status
     */
    public function test_destination_is_favorited_by_traveler()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $creator = User::factory()->create();
        $destination = Destination::factory()->create(['created_by' => $creator->id]);

        $this->actingAs($traveler);

        // Initially not favorited
        $this->assertFalse($destination->isFavorited());
    }
}
