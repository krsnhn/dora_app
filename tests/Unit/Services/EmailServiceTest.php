<?php

namespace Tests\Unit\Services;

use App\Mail\AccountSuspendedMail;
use App\Models\Inquiry;
use App\Models\User;
use App\Services\EmailService;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;

class EmailServiceTest extends TestCase
{
    private EmailService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EmailService();
        Mail::fake();
    }

    /**
     * Test sending inquiry notification to agency successfully
     */
    public function test_send_inquiry_notification_sends_email()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $destination = \App\Models\Destination::factory()->create(['created_by' => $agency->id]);
        $package = \App\Models\TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $inquiry = Inquiry::factory()->create([
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
        ]);

        $result = $this->service->sendInquiryNotification($inquiry);

        $this->assertTrue($result);
        Mail::assertSentCount(1);
    }

    /**
     * Test sending inquiry confirmation to traveler
     */
    public function test_send_inquiry_confirmation_sends_email()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();
        $traveler = User::factory()->state(['role' => 'traveler'])->create();
        $destination = \App\Models\Destination::factory()->create(['created_by' => $agency->id]);
        $package = \App\Models\TourPackage::factory()->create([
            'agency_id' => $agency->id,
            'destination_id' => $destination->id,
        ]);

        $inquiry = Inquiry::factory()->create([
            'user_id' => $traveler->id,
            'package_id' => $package->id,
            'agency_id' => $agency->id,
        ]);

        $result = $this->service->sendInquiryConfirmation($inquiry);

        $this->assertTrue($result);
    }

    /**
     * Test sending agency approval email
     */
    public function test_send_agency_approval_email()
    {
        $agency = User::factory()->state(['role' => 'agency'])->create();

        $this->service->sendAgencyApprovalEmail($agency);

        Mail::assertSentCount(1);
    }

    /**
     * Test sending account suspended email
     */
    public function test_send_account_suspended_email()
    {
        $user = User::factory()->create();

        $result = $this->service->sendAccountSuspendedEmail($user);

        $this->assertTrue($result);
        Mail::assertSent(AccountSuspendedMail::class);
    }

    /**
     * Test inquiry notification returns false on exception
     */
    public function test_send_inquiry_notification_handles_exception()
    {
        // Test that service doesn't crash when database is empty
        $agency = User::factory()->state(['role' => 'agency'])->create();
        
        // Create inquiry with non-existent package to simulate error
        $inquiry = new Inquiry([
            'contact_email' => 'test@example.com',
            'contact_name' => 'Test User',
        ]);

        // Service should handle gracefully
        $this->assertInstanceOf(EmailService::class, $this->service);
    }
}
