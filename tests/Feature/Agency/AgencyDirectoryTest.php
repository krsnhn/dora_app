<?php

namespace Tests\Feature\Agency;

use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\User;
use Tests\TestCase;

class AgencyDirectoryTest extends TestCase
{
    public function test_traveler_can_view_verified_agencies_and_their_public_packages()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'business_name' => 'Summit Trails Travel',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();
        $destination = Destination::factory()->create([
            'name' => 'Bohol',
            'created_by' => $agency->id,
            'is_approved' => true,
        ]);
        TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Chocolate Hills Escape',
            'status' => 'active',
        ]);

        // Index shows agency card
        $index = $this->actingAs($traveler)->get('/agencies');
        $index->assertStatus(200);
        $index->assertSee('Summit Trails Travel');

        // Show page lists the package
        $show = $this->actingAs($traveler)->get('/agencies/' . $agency->id);
        $show->assertStatus(200);
        $show->assertSee('Chocolate Hills Escape');
        $show->assertSee('Bohol');
        $show->assertSee('Inquire Now');
    }

    public function test_traveler_directory_hides_unapproved_or_unlisted_agencies_and_packages()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $approvedAgency = User::factory()->state([
            'role' => 'agency',
            'business_name' => 'Visible Agency',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();
        $visibleDestination = Destination::factory()->create([
            'created_by' => $approvedAgency->id,
            'is_approved' => true,
        ]);
        TourPackage::factory()->create([
            'agency_id' => $approvedAgency->id,
            'destination_id' => $visibleDestination->id,
            'name' => 'Visible Package',
            'status' => 'active',
        ]);
        TourPackage::factory()->create([
            'agency_id' => $approvedAgency->id,
            'destination_id' => $visibleDestination->id,
            'name' => 'Hidden Inactive Package',
            'status' => 'inactive',
        ]);

        $suspendedAgency = User::factory()->state([
            'role' => 'agency',
            'business_name' => 'Suspended Agency',
            'agency_status' => 'approved',
            'status' => 'suspended',
        ])->create();
        $suspendedDestination = Destination::factory()->create([
            'created_by' => $suspendedAgency->id,
            'is_approved' => true,
        ]);
        TourPackage::factory()->create([
            'agency_id' => $suspendedAgency->id,
            'destination_id' => $suspendedDestination->id,
            'name' => 'Suspended Package',
            'status' => 'active',
        ]);

        $pendingAgency = User::factory()->state([
            'role' => 'agency',
            'business_name' => 'Pending Agency',
            'agency_status' => 'pending',
            'status' => 'active',
        ])->create();
        $pendingDestination = Destination::factory()->create([
            'created_by' => $pendingAgency->id,
            'is_approved' => true,
        ]);
        TourPackage::factory()->create([
            'agency_id' => $pendingAgency->id,
            'destination_id' => $pendingDestination->id,
            'name' => 'Pending Package',
            'status' => 'active',
        ]);

        $response = $this->actingAs($traveler)->get('/agencies');

        $response->assertStatus(200);
        $response->assertSee('Visible Agency');
        $response->assertDontSee('Suspended Agency');
        $response->assertDontSee('Pending Agency');

        // Show page for visible agency does not show hidden packages
        $show = $this->actingAs($traveler)->get('/agencies/' . $approvedAgency->id);
        $show->assertStatus(200);
        $show->assertSee('Visible Package');
        $show->assertDontSee('Hidden Inactive Package');
    }

    public function test_admin_can_view_all_agencies_and_attached_packages()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'business_name' => 'Admin Review Agency',
            'agency_status' => 'rejected',
            'status' => 'suspended',
        ])->create();
        $destination = Destination::factory()->create([
            'name' => 'Siargao',
            'created_by' => $agency->id,
            'is_approved' => true,
        ]);
        TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Hidden Admin Package',
            'status' => 'inactive',
        ]);

        $index = $this->actingAs($admin)->get('/admin/agencies');
        $index->assertStatus(200);
        $index->assertSee('Admin Review Agency');
        $index->assertSee('Rejected');
        $index->assertSee('Suspended');

        $show = $this->actingAs($admin)->get('/admin/agencies/' . $agency->id);
        $show->assertStatus(200);
        $show->assertSee('Hidden Admin Package');
        $show->assertSee('Siargao');
    }

    public function test_admin_agency_profile_returns_404_for_non_agency_user()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $traveler = User::factory()->state(['role' => 'traveler'])->create();

        $this->actingAs($admin)
            ->get('/admin/agencies/' . $traveler->id)
            ->assertStatus(404);
    }

    public function test_traveler_can_view_agency_profile_page_with_packages()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'business_name' => 'Coastal Wanderers',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();
        $destination = Destination::factory()->create([
            'name' => 'Palawan',
            'created_by' => $agency->id,
            'is_approved' => true,
        ]);
        TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Island Hopping Adventure',
            'status' => 'active',
        ]);

        $response = $this->actingAs($traveler)->get('/agencies/' . $agency->id);

        $response->assertStatus(200);
        $response->assertSee('Coastal Wanderers');
        $response->assertSee('Island Hopping Adventure');
        $response->assertSee('Palawan');
        $response->assertSee('Inquire Now');
    }

    public function test_agency_profile_returns_404_for_suspended_agency()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $suspended = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
            'status' => 'suspended',
        ])->create();

        $this->actingAs($traveler)->get('/agencies/' . $suspended->id)->assertStatus(404);
    }

    public function test_agency_profile_returns_404_for_pending_agency()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $pending = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'pending',
            'status' => 'active',
        ])->create();

        $this->actingAs($traveler)->get('/agencies/' . $pending->id)->assertStatus(404);
    }
}
