<?php

namespace Database\Factories;

use App\Models\TourPackage;
use App\Models\User;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TourPackage>
 */
class TourPackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\TourPackage>
     */
    protected $model = TourPackage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agency_id' => User::factory()->state(['role' => 'agency']),
            'destination_id' => Destination::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1000, 10000),
            'duration' => $this->faker->numberBetween(1, 14),
            'inclusions' => 'Hotel, meals, tours, transportation',
            'image_url' => $this->faker->imageUrl(640, 480, 'travel', true),
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the package is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
