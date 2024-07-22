<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Student;
use App\Models\Classe;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportController extends Controller
{
    public function importListStudents(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'classe_id' => 'integer',
            'ecole_id' => 'integer'
        ]);

        $data = $request->file;
        $classe = Classe::find((int) $request->classe_id);

        for ($i=0; $i < count($data); $i++) { 
            $student = Student::create([
                'matricule' => $data[$i][0],
                'nom' => $data[$i][1],
                'prenom' => $data[$i][2],
                'date_naissance' => $data[$i][3],
                'lieu_naissance' => $data[$i][4],
                'sexe' => $data[$i][5],
                'date_scolarisation' => $request->annee_scolaire,
                'classe_id' => (int) $request->classe_id,
                'ecole_id' => (int) $request->ecole_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        $classe->effectif = count($data);
        $classe->update();

        return response()->json([
            'message' => 'Données importées avec succès !'
        ], 200);
    }

    private function getActiveSheet(string $path): WorkSheet
    {
        return (new Xlsx)
            ->load($path)
            ->getActiveSheet();
    }
}
