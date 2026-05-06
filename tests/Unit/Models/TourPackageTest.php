<?php

namespace Tests\Unit\Models;

use App\Models\TourPackage;
use App\Models\User;
use App\Models\Destination;
use Tests\TestCase;

class TourPackageTest extends TestCase
{
    /**
     * Test tour package belongs to agency
     */
    public function test_tour_package_belongs_to_agency()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $this->assertInstanceOf(User::class, $package->agency);
        $this->assertEquals($agency->id, $package->agency->id);
    }

    /**
     * Test tour package belongs to destination
     */
    public function test_tour_package_belongs_to_destination()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $this->assertInstanceOf(Destination::class, $package->destination);
        $this->assertEquals($destination->id, $package->destination->id);
    }

    /**
     * Test tour package scope active
     */
    public function test_tour_package_scope_active()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);

        TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'status' => 'active',
        ]);

        TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'status' => 'inactive',
        ]);

        $active = TourPackage::active()->count();

        $this->assertEquals(1, $active);
    }

    /**
     * Test tour package fillable attributes
     */
    public function test_tour_package_fillable_attributes()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Beach Tour',
            'price' => 5000.00,
            'duration' => 3,
            'status' => 'active',
        ]);

        $this->assertEquals('Beach Tour', $package->name);
        $this->assertEquals(5000.00, $package->price);
        $this->assertEquals(3, $package->duration);
        $this->assertEquals('active', $package->status);
    }

    /**
     * Test tour package display image attribute
     */
    public function test_tour_package_display_image_attribute()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create([
            'created_by' => $agency->id,
            'image_url' => 'https://example.com/destination.jpg',
        ]);
        
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'image_url' => 'https://example.com/package.jpg',
        ]);

        $this->assertEquals('https://example.com/package.jpg', $package->display_image);
    }

    /**
     * Test tour package uses destination image if own image not available
     */
    public function test_tour_package_uses_destination_image_if_unavailable()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create([
            'created_by' => $agency->id,
            'image_url' => 'https://example.com/destination.jpg',
        ]);
        
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'image_url' => null,
        ]);

        $this->assertEquals('https://example.com/destination.jpg', $package->display_image);
    }
}
