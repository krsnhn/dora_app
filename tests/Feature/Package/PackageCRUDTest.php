<?php

namespace Tests\Feature\Package;

use App\Models\User;
use App\Models\Destination;
use App\Models\TourPackage;
use Tests\TestCase;

class PackageCRUDTest extends TestCase
{
    /**
     * Test agency can create tour package
     */
    public function test_agency_can_create_tour_package()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);

        $response = $this->actingAs($agency)->post('/agency/packages', [
            'name' => 'Beach Paradise Tour',
            'destination_id' => $destination->id,
            'price' => 5000.00,
            'duration' => 3,
            'description' => 'A wonderful beach experience',
            'inclusions' => 'Hotel, meals, tours',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('tour_packages', [
            'agency_id' => $agency->id,
            'name' => 'Beach Paradise Tour',
            'price' => 5000.00,
        ]);
    }

    /**
     * Test traveler cannot create tour package
     */
    public function test_traveler_cannot_create_tour_package()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);

        $response = $this->actingAs($traveler)->post('/agency/packages', [
            'name' => 'Beach Tour',
            'destination_id' => $destination->id,
            'price' => 5000.00,
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test agency can view own tour packages
     */
    public function test_agency_can_view_own_tour_packages()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($agency)->get('/agency/packages');

        $response->assertStatus(200);
    }

    /**
     * Test agency can edit own tour package
     */
    public function test_agency_can_edit_own_tour_package()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($agency)->patch("/agency/packages/{$package->id}", [
            'name' => 'Updated Package Name',
            'destination_id' => $destination->id,
            'price' => 6000.00,
            'duration' => 5,
            'description' => 'Updated description for this tour package.',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('tour_packages', [
            'id' => $package->id,
            'name' => 'Updated Package Name',
            'price' => 6000.00,
        ]);
    }

    /**
     * Test agency cannot edit other agency's package
     */
    public function test_agency_cannot_edit_other_agency_package()
    {
        $agency1 = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $agency2 = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency1->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency1->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($agency2)->patch("/agency/packages/{$package->id}", [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test agency can delete own tour package
     */
    public function test_agency_can_delete_own_tour_package()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($agency)->delete("/agency/packages/{$package->id}");

        // destroy() uses soft-delete pattern: sets status = 'deleted'
        $this->assertDatabaseHas('tour_packages', [
            'id' => $package->id,
            'status' => 'deleted',
        ]);
    }

    /**
     * Test package must have required fields
     */
    public function test_package_creation_requires_required_fields()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $response = $this->actingAs($agency)->post('/agency/packages', [
            // Missing required fields
        ]);

        $response->assertSessionHasErrors(['name', 'destination_id', 'price']);
    }

    /**
     * Test active packages scope works correctly
     */
    public function test_active_packages_scope()
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

        $activeCount = TourPackage::active()->count();

        $this->assertEquals(1, $activeCount);
    }

    /**
     * Test package price validation
     */
    public function test_package_price_must_be_positive()
    {
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);

        $response = $this->actingAs($agency)->post('/agency/packages', [
            'name' => 'Invalid Package',
            'destination_id' => $destination->id,
            'price' => -100,
        ]);

        $response->assertSessionHasErrors('price');
    }
}
