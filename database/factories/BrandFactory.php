<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = (new \Faker\Factory())::create();
        $this->faker->addProvider(new \Faker\Provider\Fakecar($this->faker));
        $name = $this->faker->unique()->vehicleBrand();

        return [
            'name' => $name,
            'country' => fake()->country(),
            'image' => fake()->imageUrl(100, 100, NULL, FALSE, $name, FALSE),
        ];
    }
}
