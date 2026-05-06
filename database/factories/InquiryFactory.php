<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\User;
use App\Models\TourPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Inquiry>
     */
    protected $model = Inquiry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'traveler']),
            'package_id' => TourPackage::factory(),
            'agency_id' => User::factory()->state(['role' => 'agency']),
            'contact_name' => $this->faker->name(),
            'contact_email' => $this->faker->safeEmail(),
            'contact_phone' => $this->faker->phoneNumber(),
            'pax' => $this->faker->numberBetween(1, 8),
            'message' => $this->faker->paragraph(),
            'travel_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            'status' => 'pending',
        ];
    }

    /**
     * Indicate that the inquiry is accepted.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    /**
     * Indicate that the inquiry is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
