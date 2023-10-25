<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Matiere;
use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\TypeClasse;
use App\Models\Tarif;
use App\Models\TypesEtablissement;
use App\Models\Student;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function getRole(int $id)
    {
        $role = Role::find($id);
        return response()->json($role, 200);
    }

    public function getTypesClasse()
    {
        $types = TypeClasse::all();
        return response()->json($types, 200);
    }

    public function getTypeClasse($id) {
        $typeClasse = TypeClasse::find($id);
        return response()->json($typeClasse, 200);
    }

    public function getMatieres(int $ecole_id)
    {
        $matieres = Matiere::where('ecole_id', $ecole_id)->get();
        return response()->json($matieres, 200);
    }

    public function addClasse(Request $req)
    {
        $classe = new Classe();
        $classe->nom = $req->nom;
        $classe->ecole_id = (int) $req->ecole_id;
        $classe->type_classe_id = (int) $req->type_classe;
        $classe->effectif = 0;
        $classe->created_at = now();
        $classe->updated_at = now();
        $classe->save();

        return response()->json('Classe enregistrée avec succès !', 200);
    }

    public function deleteClasse(int $id)
    {
        $classe = Classe::find($id);
        $classe->delete();

        return response()->json('Suppression réussie !', 200);
    }

    public function getClassesSchool($ecole_id)
    {
        $classes = DB::table('classes')
            ->join('type_classes', 'classes.type_classe_id', '=', 'type_classes.id')
            ->join('ecoles', 'classes.ecole_id', '=', 'ecoles.id')
            ->select('classes.*', 'type_classes.classe', 'ecoles.nom as nom_ecole')
            ->where('ecole_id', $ecole_id)
            ->get();

        return response()->json($classes, 200);
    }

    public function getInfoClasse(int $id_classe)
    {
        $classe = Classe::find($id_classe);
        return response()->json($classe, 200);
    }

    public function getEmployesOfSchool(int $ecole_id)
    {
        $employes = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.intitule as role')
            ->where('ecole_id', $ecole_id)
            ->get();

        return response()->json($employes, 200);
    }

    public function getEnseignants(int $ecole_id)
    {
        $users = User::all()
            ->where('ecole_id', $ecole_id)
            ->where('classe_id', '!=', NULL);

        return response()->json($users, 200);
    }

    public function profileEmploye($id)
    {
        $employe = User::find($id);
        return response()->json($employe, 200);
    }

    public function getInfoEcole($id)
    {
        $ecole = Ecole::find($id);
        return response()->json($ecole, 200);
    }

    public function getTypesEtablissement()
    {
        $typesEtablissement = TypesEtablissement::all();

        return response()->json($typesEtablissement, 200);
    }

    public function addEcole(Request $req)
    {
        $ecole = new Ecole();

        $ecole->nom = $req->nom;
        $ecole->localisation = $req->localisation;
        $ecole->ville = $req->ville;
        $ecole->type_etablissement_id = (int) $req->type_etablissement;
        $ecole->created_at = now();
        $ecole->updated_at = now();
        $ecole->save();

        return response()->json([
            'message' => 'Ecole créée avec succès !',
            'data' => $ecole
        ]);
    }

    public function addMatiere(Request $req)
    {
        $matiere = new Matiere();
        $matiere->code = $req->code;
        $matiere->intitule = $req->intitule;
        $matiere->ecole_id = $req->ecole_id;
        $matiere->coefficient = $req->coefficient;
        $matiere->created_at = now();
        $matiere->updated_at = now();
        $matiere->save();

        return response()->json($matiere, 200);
    }

    public function getStudents($ecole_id)
    {
        $students = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('students.*', 'classes.nom as nom_classe')
            ->where('students.ecole_id', $ecole_id)
            ->get();

        return response()->json($students, 200);
    }

    public function getStudent($id)
    {
        $student = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->join('type_classes', 'classes.type_classe_id', '=', 'type_classes.id')
            ->select('students.*', 'classes.nom as nom_classe', 'type_classes.classe as type_classe')
            ->where('students.id', $id)
            ->get();

        return response()->json($student, 200);
    }

    public function getParents($ecole_id)
    {
        $parents = DB::table('users')
            ->join('students', 'users.student_id', '=', 'students.id')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('users.*', 'students.nom as nom_student', 'students.prenom as prenom_student', 'classes.nom as nom_classe')
            ->where('users.ecole_id', $ecole_id)
            ->get();

        return response()->json($parents, 200);
    }

    public function getParent($id)
    {
        $parent = DB::table('users')
            ->join('students', 'users.student_id', '=', 'students.id')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('users.*', 'students.nom as nom_student', 'students.prenom as prenom_student', 'classes.nom as nom_classe')
            ->where('users.id', $id)
            ->get();

        return response()->json($parent, 200);
    }

    public function getTarifs($ecole_id)
    {
        $tarifs = DB::table('tarifs')
            ->join('classes', 'tarifs.classe_id', '=', 'classes.id')
            ->select('tarifs.*', 'classes.nom as classe')
            ->where('tarifs.ecole_id', $ecole_id)
            ->get();

        return response()->json($tarifs, 200);
    }

    public function addTarif(Request $req)
    {
        $tarif = new Tarif();

        $tarif->classe_id = (int) $req->classe;
        $tarif->inscription = (int) $req->inscription;
        $tarif->premiere_tranche = (int) $req->premiere_tranche;
        $tarif->deuxieme_tranche = (int) $req->deuxieme_tranche;
        $tarif->troisieme_tranche = (int) $req->troisieme_tranche;
        $tarif->ecole_id = (int) $req->ecole;
        $tarif->created_at = now();
        $tarif->updated_at = now();
        $tarif->save();

        return response()->json([
            'message' => 'Tarif enregistré avec succès !',
            'data' => $tarif
        ]);
    }

    public function addPaiement(Request $req)
    {
        $paiement = new Paiement();

        $paiement->code = Str::random(10);
        $paiement->intitule = $req->intitule;
        $paiement->montant = (int) $req->montant;
        $paiement->created_at = now();
        $paiement->updated_at = now();
        $paiement->student_id = (int) $req->student;
        $paiement->ecole_id = (int) $req->ecole;
        $paiement->save();

        return response()->json([
            'message' => 'Paiement enregistré avec succès !',
            'data' => $paiement
        ]);
    }

    public function getPaiements($ecole_id)
    {
        $paiements = DB::table('paiements')
            ->join('students', 'paiements.student_id', '=', 'students.id')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->join('tarifs', 'classes.id', '=', 'tarifs.classe_id')
            ->select('paiements.*', 'students.nom as nom_student', 'students.prenom as prenom_student', 'paiements.intitule as designation')
            ->select('tarifs.*', 'classes.nom as nom_classe')
            ->where('paiements.ecole_id', $ecole_id)
            ->get();

        return response()->json($paiements, 200);
    }
}