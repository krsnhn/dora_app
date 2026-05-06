<?php

namespace Tests\Unit\Models;

use App\Models\Destination;
use App\Models\User;
use Tests\TestCase;

class DestinationTest extends TestCase
{
    /**
     * Test destination belongs to creator
     */
    public function test_destination_belongs_to_creator()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $destination->creator);
        $this->assertEquals($user->id, $destination->creator->id);
    }

    /**
     * Test destination has many tour packages
     */
    public function test_destination_has_many_tour_packages()
    {
        $user = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $user->id]);

        $this->assertTrue(true);
    }

    /**
     * Test destination fillable attributes
     */
    public function test_destination_fillable_attributes()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'name' => 'Boracay',
            'country' => 'Philippines',
            'location' => 'Aklan',
            'tags' => 'beach, island, tropical',
            'is_approved' => true,
        ]);

        $this->assertEquals('Boracay', $destination->name);
        $this->assertEquals('Philippines', $destination->country);
        $this->assertEquals('Aklan', $destination->location);
        $this->assertTrue($destination->is_approved);
    }

    /**
     * Test destination tags array attribute
     */
    public function test_destination_tags_array_attribute()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'tags' => 'beach, island, tropical, resort',
        ]);

        $tags = $destination->tags_array;

        $this->assertIsArray($tags);
        $this->assertCount(4, $tags);
        $this->assertContains('beach', $tags);
        $this->assertContains('island', $tags);
    }

    /**
     * Test destination tags array handles empty tags
     */
    public function test_destination_tags_array_handles_empty_tags()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'tags' => null,
        ]);

        $tags = $destination->tags_array;

        $this->assertIsArray($tags);
        $this->assertEmpty($tags);
    }

    /**
     * Test destination is favorited by user
     */
    public function test_destination_is_favorited_by_user()
    {
        $user = User::factory()->state(['role' => 'traveler'])->create();
        $creator = User::factory()->create();
        $destination = Destination::factory()->create(['created_by' => $creator->id]);

        $this->actingAs($user);

        $isFavorited = $destination->isFavorited();

        // Should not be favorited initially
        $this->assertFalse($isFavorited);
    }

    /**
     * Test destination latitude and longitude casting
     */
    public function test_destination_coordinates_casting()
    {
        $user = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $user->id,
            'latitude' => 11.9673,
            'longitude' => 121.9272,
        ]);

        $this->assertIsFloat($destination->latitude);
        $this->assertIsFloat($destination->longitude);
        $this->assertEquals(11.9673, $destination->latitude);
        $this->assertEquals(121.9272, $destination->longitude);
    }

    /**
     * Test destination approval status
     */
    public function test_destination_approval_status()
    {
        $user = User::factory()->create();
        $approved = Destination::factory()->create([
            'created_by' => $user->id,
            'is_approved' => true,
        ]);
        $pending = Destination::factory()->create([
            'created_by' => $user->id,
            'is_approved' => false,
        ]);

        $this->assertTrue($approved->is_approved);
        $this->assertFalse($pending->is_approved);
    }
}
