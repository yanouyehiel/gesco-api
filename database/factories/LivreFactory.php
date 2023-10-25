<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class livreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'intitule' => $this->faker->title,
            'ecole_id' => 1
        ];
    }
}
