<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semestre;
use App\Models\Departement;
use App\Models\Cursus;
use App\Models\Cycle;
use App\Models\Filiere;
use App\Models\TypeClasse;
use App\Models\ProcesVerbal;
use Illuminate\Support\Facades\DB;

class UniversityController extends Controller
{
    public function listSemestres()
    {
        $semestres = Semestre::all();

        return response()->json($semestres, 200);
    }

    public function createDepartement(Request $req)
    {
        $depart = new Departement();
        $depart->intitule = $req->intitule;
        $depart->ecole_id = (int) $req->ecole_id;
        $depart->responsable_id = (int) $req->responsable_id;
        $depart->save();

        return response()->json([
            'message' => "Département enregistré !",
            'data' => $depart
        ], 201);
    }

    public function listDepartements($ecole_id)
    {
        $departs = Departement::where('ecole_id', (int) $ecole_id)->get();

        return response()->json($departs, 200);
    }

    public function createCursus(Request $req)
    {
        $cursus = new Cycle();
        $cursus->intitule = $req->intitule;
        $cursus->ecole_id = (int) $req->ecole_id;
        $cursus->save();

        return response()->json($cursus, 201);
    }

    public function createTypeClasse(Request $req)
    {
        $type = new TypeClasse();
        $type->classe = $req->classe;
        $type->ecole_id = (int) $req->ecole_id;
        $type->save();

        return response()->json([
            'message' => "Type classe crée !",
            'data' => $type
        ], 201);
    }

    public function listCursus($ecole_id)
    {
        $cursus = Cursus::where('ecole_id', (int) $ecole_id)->get();

        return response()->json($cursus, 200);
    }

    public function createFiliere(Request $req)
    {
        $filiere = new Filiere();
        $filiere->cursus_id = (int) $req->cursus_id;
        $filiere->domaine = $req->domaine;
        $filiere->departement_id = (int) $req->departement_id;
        $filiere->specialite = $req->specialite;
        $filiere->parcours = $req->parcours;
        $filiere->option = $req->option;
        $filiere->ecole_id = (int) $req->ecole_id;
        $filiere->save();

        return response()->json([
            'message' => "Filière enregistrée !",
            'data' => $filiere
        ], 201);
    }

    public function listFiliere($ecole_id)
    {
        $filieres = Filiere::where('ecole_id', (int) $ecole_id)->get();

        return response()->json($filieres, 200);
    }

    public function saveNote(Request $req)
    {
        $note = new ProcesVerbal();
        $note->note = $req->note;
        $note->matiere_id = (int) $req->matiere_id;
        $note->student_id = (int) $req->student_id;
        $note->classe_id = (int) $req->classe_id;
        $note->semestre_id = (int) $req->semestre_id;
        isset($req->appreciation) && $note->appreciation = $req->appreciation;
        $note->type = $req->type;
        $note->ecole_id = (int) $req->ecole_id;
        $note->annee_scolaire = $req->annee_scolaire;
        $note->save();

        return response()->json([
            'message' => "Note enregistrée !",
            'data' => $note
        ], 201);
    }

    public function listNote($ecole_id, $classe_id, $student_id, $semestre_id, $matiere_id, $annee)
    {
        $query = DB::table('proces_verbals')
            ->join('matieres', 'proces_verbals.matiere_id', '=', 'matieres.id')
            ->join('students', 'proces_verbals.student_id', '=', 'students.id')
            //->join('semestres', 'notes.semestre_id', '=', 'semestres.id');
            ->select('proces_verbals.*', 'matieres.intitule as intitule_matiere', 'matieres.code as code_matiere', 'students.nom as nom_student', 'students.prenom as prenom_student')
            ->where('proces_verbals.ecole_id', (int) $ecole_id)
            ->where('proces_verbals.annee_scolaire', $annee)
            ->when($classe_id !== 'null', function ($query) use ($classe_id) {
                return $query->where('proces_verbals.classe_id', (int) $classe_id);
            })
            ->when($semestre_id !== 'null', function ($query) use ($semestre_id) {
                return $query->where('proces_verbals.semestre_id', (int) $semestre_id);
            })
            ->when($matiere_id !== 'null', function ($query) use ($matiere_id) {
                return $query->where('proces_verbals.matiere_id', (int) $matiere_id);
            })
            ->get();

        $department = Departement::where('ecole_id', (int) $ecole_id)->first();
        $semestre = Semestre::find((int) $semestre_id);

        return response()->json([
            'annee_scolaire' => $annee,
            'departement' => $department->intitule,
            'semestre' => $semestre->intitule,
            'notes' => $query
        ], 200);
    }
}
