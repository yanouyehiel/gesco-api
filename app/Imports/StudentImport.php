<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'matricule' => $row[0],
            'nom' => $row[1],
            'prenom' => $row[2],
            'date_naissance' => $row[3],
            'lieu_naissance' => $row[4],
            'sexe' => $row[5]
        ]);
    }
}
