<?php

namespace Tests\Feature\Admin;

use App\Mail\AccountSuspendedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserStatusNotificationTest extends TestCase
{
    public function test_suspending_an_agency_sends_a_notification_email()
    {
        Mail::fake();

        $admin = User::factory()->state(['role' => 'admin'])->create();
        $agency = User::factory()->state([
            'role' => 'agency',
            'agency_status' => 'approved',
            'status' => 'active',
        ])->create();

        $response = $this->actingAs($admin)->patch("/admin/users/{$agency->id}/toggle-status");

        $response->assertRedirect();
        $this->assertSame('suspended', $agency->fresh()->status);
        Mail::assertSent(AccountSuspendedMail::class, fn ($mail) => $mail->hasTo($agency->email));
    }

    public function test_suspending_a_traveler_sends_a_notification_email()
    {
        Mail::fake();

        $admin = User::factory()->state(['role' => 'admin'])->create();
        $traveler = User::factory()->state([
            'role' => 'traveler',
            'status' => 'active',
        ])->create();

        $response = $this->actingAs($admin)->patch("/admin/users/{$traveler->id}/toggle-status");

        $response->assertRedirect();
        $this->assertSame('suspended', $traveler->fresh()->status);
        Mail::assertSent(AccountSuspendedMail::class, fn ($mail) => $mail->hasTo($traveler->email));
    }
}