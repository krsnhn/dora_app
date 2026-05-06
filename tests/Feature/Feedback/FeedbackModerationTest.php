<?php

namespace Tests\Feature\Feedback;

use App\Models\Destination;
use App\Models\Feedback;
use App\Models\TourPackage;
use App\Models\User;
use Tests\TestCase;

class FeedbackModerationTest extends TestCase
{
    public function test_traveler_can_submit_feedback_and_it_is_pending()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();
        $destinationOwner = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $destinationOwner->id,
            'is_approved' => true,
        ]);
        $package = TourPackage::create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Island Escape',
            'description' => 'A beach getaway package for testing feedback.',
            'price' => 2500,
            'duration' => '3 days',
            'status' => 'active',
        ]);

        $response = $this->actingAs($traveler)->post('/feedback', [
            'agency_id' => $agency->id,
            'package_id' => $package->id,
            'rating' => 5,
            'comment' => 'Excellent package and smooth booking process.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'user_id' => $traveler->id,
            'agency_id' => $agency->id,
            'package_id' => $package->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_view_pending_feedback_and_approve_it()
    {
        $admin = User::factory()->state(['role' => 'admin'])->create();
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();
        $destinationOwner = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $destinationOwner->id,
            'is_approved' => true,
            'name' => 'Palawan',
        ]);
        $package = TourPackage::create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Sunset Cruise',
            'description' => 'A cruise package for moderation testing.',
            'price' => 3200,
            'duration' => '2 days',
            'status' => 'active',
        ]);
        $feedback = Feedback::create([
            'user_id' => $traveler->id,
            'agency_id' => $agency->id,
            'package_id' => $package->id,
            'rating' => 4,
            'comment' => 'Worth the trip.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin/feedback');

        $response->assertStatus(200);
        $response->assertSee('Worth the trip.');
        $response->assertSee('Palawan');
        $response->assertSee('Sunset Cruise');

        $updateResponse = $this->actingAs($admin)->patch("/admin/feedback/{$feedback->id}", [
            'status' => 'approved',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'status' => 'approved',
        ]);
    }

    public function test_destination_page_only_shows_approved_feedback()
    {
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();
        $destinationOwner = User::factory()->create();
        $destination = Destination::factory()->create([
            'created_by' => $destinationOwner->id,
            'is_approved' => true,
        ]);
        $package = TourPackage::create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
            'name' => 'Adventure Trail',
            'description' => 'A mountain adventure package for visibility tests.',
            'price' => 1800,
            'duration' => '2 days',
            'status' => 'active',
        ]);

        Feedback::create([
            'user_id' => $traveler->id,
            'agency_id' => $agency->id,
            'package_id' => $package->id,
            'rating' => 5,
            'comment' => 'Approved feedback comment',
            'status' => 'approved',
        ]);

        Feedback::create([
            'user_id' => $traveler->id,
            'agency_id' => $agency->id,
            'package_id' => $package->id,
            'rating' => 2,
            'comment' => 'Pending feedback comment',
            'status' => 'pending',
        ]);

        $response = $this->get("/destinations/{$destination->id}");

        $response->assertStatus(200);
        $response->assertSee('Approved feedback comment');
        $response->assertDontSee('Pending feedback comment');
    }
}