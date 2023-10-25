<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;
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
            'role_id' => 1,
            'ecole_id' => 1,
            'classe_id' => 1,
            'created_at' => now()
        ];
    }
}
