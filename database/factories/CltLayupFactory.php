<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CltLayup>
 */
class CltLayupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Layup ' . $this->faker->unique()->word()
        ];
    }
}
