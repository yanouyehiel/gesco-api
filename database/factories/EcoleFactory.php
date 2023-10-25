<?php

namespace Database\Factories;

use App\Models\Ecole;
use Illuminate\Database\Eloquent\Factories\Factory;

class EcoleFactory extends Factory
{
    protected $model = Ecole::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->company,
            'localisation' => $this->faker->address,
            'ville' => $this->faker->city,
            'created_at' => now()
        ];
    }
}
