<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $placeHolders = [
            'Alfa_Romeo_Giulia.jpg',
            'Audi_100.jpeg',
            'Ford_GT.jpg',
            'Ford_Model_T.jpg',
            'Lancia_Fulvia.jpg',
            'Maserati_Biturbo.jpg',
            'Mercedes_S_Klasse.jpg',
            'Volvo_S80.jpg',
        ];
        $image = $placeHolders[array_rand($placeHolders, 1)];

        return [
            'image' => 'placeholders/' . $image,
            'location' => fake()->city(),
        ];
    }
}
