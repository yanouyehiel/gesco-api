<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;
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
            'date_naissance' => '02-03-2005',
            'date_scolarisation' => '2022-2023',
            'classe_id' => 5,
            'ecole_id' => 1
        ];
    }
}
