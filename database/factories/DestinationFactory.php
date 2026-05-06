<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Destination>
     */
    protected $model = Destination::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'country' => $this->faker->country(),
            'location' => $this->faker->state(),
            'description' => $this->faker->paragraph(),
            'image_url' => $this->faker->imageUrl(640, 480, 'travel', true),
            'tags' => 'beach,travel,tourism,destination',
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'weather_location' => $this->faker->city(),
            'is_approved' => false,
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the destination is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
        ]);
    }
}
