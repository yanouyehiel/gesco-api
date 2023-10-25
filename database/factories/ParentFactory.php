<?php

namespace Database\Factories;

use App\Models\ParentStudent;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParentFactory extends Factory
{
    protected $model = ParentStudent::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'matricule' => $this->faker->postcode,
            'nom' => $this->faker->firstname,
            'prenom' => $this->faker->lastname,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'admin1', // password
            'student_id' => 1,
            'remember_token' => Str::random(10),
            'created_at' => now()
        ];
    }
}
