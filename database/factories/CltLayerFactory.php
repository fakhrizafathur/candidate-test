<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CltLayer>
 */
class CltLayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'layer_order' => 1, // nanti kita override
            'thickness' => $this->faker->randomFloat(2, 10, 50),
            'width' => $this->faker->randomFloat(2, 80, 200),
            'angle' => $this->faker->randomElement([0, 45, 90])
        ];
    }
}