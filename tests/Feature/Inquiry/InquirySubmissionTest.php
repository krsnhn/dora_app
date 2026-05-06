<?php

namespace Tests\Feature\Inquiry;

use App\Models\User;
use App\Models\Destination;
use App\Models\TourPackage;
use App\Models\Inquiry;
use App\Services\EmailService;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class InquirySubmissionTest extends TestCase
{
    /**
     * Test traveler can submit inquiry
     */
    public function test_traveler_can_submit_inquiry()
    {
        Mail::fake();

        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($traveler)->post("/inquiries/{$package->id}", [
            'contact_name' => 'John Traveler',
            'contact_email' => 'john@example.com',
            'contact_phone' => '1234567890',
            'pax' => 4,
            'message' => 'Interested in this tour package',
            'travel_date' => '2026-06-15',
        ]);

        $this->assertDatabaseHas('inquiries', [
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
            'contact_name' => 'John Traveler',
        ]);
    }

    /**
     * Test inquiry status defaults to pending
     */
    public function test_inquiry_status_defaults_to_pending()
    {
        Mail::fake();

        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($traveler)->post("/inquiries/{$package->id}", [
            'contact_name' => 'Test User',
            'contact_email' => 'test@example.com',
            'contact_phone' => '1234567890',
            'pax' => 2,
            'travel_date' => '2026-08-01',
            'message' => 'I am interested in this tour package.',
        ]);

        $inquiry = Inquiry::latest()->first();

        $this->assertEquals('pending', $inquiry->status);
    }

    /**
     * Test inquiry requires mandatory fields
     */
    public function test_inquiry_submission_validates_required_fields()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($traveler)->post("/inquiries/{$package->id}", [
            // Missing required fields
        ]);

        $response->assertSessionHasErrors(['contact_name', 'contact_email', 'contact_phone', 'travel_date', 'message']);
    }

    /**
     * Test inquiry must have valid email
     */
    public function test_inquiry_email_validation()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($traveler)->post("/inquiries/{$package->id}", [
            'contact_name' => 'Test',
            'contact_email' => 'invalid-email',
            'contact_phone' => '1234567890',
        ]);

        $response->assertSessionHasErrors('contact_email');
    }

    /**
     * Test pax must be positive integer
     */
    public function test_inquiry_pax_must_be_positive()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->actingAs($traveler)->post("/inquiries/{$package->id}", [
            'contact_name' => 'Test',
            'contact_email' => 'test@example.com',
            'contact_phone' => '1234567890',
            'pax' => 0,
        ]);

        $response->assertSessionHasErrors('pax');
    }

    /**
     * Test traveler can view own inquiries
     */
    public function test_traveler_can_view_own_inquiries()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        Inquiry::factory()->create([
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
        ]);

        $response = $this->actingAs($traveler)->get('/my-inquiries');

        $response->assertStatus(200);
    }

    /**
     * Test agency can view received inquiries
     */
    public function test_agency_can_view_received_inquiries()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        Inquiry::factory()->create([
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
        ]);

        $response = $this->actingAs($agency)->get('/agency/inquiries');

        $response->assertStatus(200);
    }

    /**
     * Test agency can update inquiry status
     */
    public function test_agency_can_update_inquiry_status()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
        ])->create();

        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $inquiry = Inquiry::factory()->create([
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($agency)->patch("/agency/inquiries/{$inquiry->id}/status", [
            'status' => 'accepted',
        ]);

        $this->assertEquals('accepted', $inquiry->fresh()->status);
    }

    /**
     * Test guest cannot submit inquiry
     */
    public function test_guest_cannot_submit_inquiry()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $response = $this->post("/inquiries/{$package->id}", [
            'contact_name' => 'Test',
            'contact_email' => 'test@example.com',
            'contact_phone' => '1234567890',
        ]);

        $response->assertRedirect('/login');
    }

    /**
     * Test inquiry relationships
     */
    public function test_inquiry_relationships()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $destination = Destination::factory()->create(['created_by' => $agency->id]);
        $package = TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $inquiry = Inquiry::factory()->create([
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
        ]);

        $this->assertInstanceOf(User::class, $inquiry->user);
        $this->assertInstanceOf(TourPackage::class, $inquiry->package);
        $this->assertInstanceOf(User::class, $inquiry->agency);
    }
}
