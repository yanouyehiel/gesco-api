<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Role;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\Classe;
use App\Models\Devoir;
use App\Models\Absence;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function getNotesOfChildren(int $student_id)
    {
        $notes = DB::table('notes')
            ->join('students', 'notes.student_id', '=', 'students.id')
            ->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
            ->select('notes.*', 'matieres.intitule as nom_matiere', 'students.nom', 'students.prenom')
            ->where('notes.student_id', $student_id)
            ->orderByDesc('notes.created_at')
            ->get();

        return response()->json([
            "notes" => $notes
        ], 200);
    }

    public function getAbsencesOfChildren($id)
    {
        $student = Student::findOrFail((int) $id);

        return response()->json([
            "student" => $student,
            "absences" => $student->absences
        ], 200);
    }

    public function getDevoirsOfChildren($classe_id)
    {
        $devoirs = DB::table('devoirs')
            ->join('livres', 'devoirs.livre_id', '=', 'livres.id')
            ->join('matieres', 'devoirs.matiere_id', '=', 'matieres.id')
            ->select('devoirs.*', 'livres.intitule as nom_livre', 'matieres.intitule as nom_matiere')
            ->where('devoirs.classe_id', (int) $classe_id)
            ->orderByDesc('devoirs.created_at')
            ->get();
            
        return response()->json($devoirs, 200);
    }

    public function getCoursOfChildren($classe_id)
    {
        $cours = DB::table('cours')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->join('users', 'cours.teacher_id', '=', 'users.id')
            ->select('cours.*', 'matieres.intitule as nom_matiere', 'users.nom as nom_teacher', 'users.prenom as prenom_teacher')
            ->where('cours.classe_id', $classe_id)
            ->orderByDesc('cours.created_at')
            ->get();

        return response()->json([
            'cours' => $cours
        ], 200);
    }

    public function getMyAllChildren($id)
    {
        $students = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('students.*', 'classes.nom as nom_classe')
            ->where('students.parent_id', (int) $id)
            ->get();

        return response()->json([
            'students' => $students
        ], 200);
    }

    public function getInfoOfChildren(Request $request)
    {
        $children = Student::all()
            ->where('id', auth()->user()->student_id);

        return response()->json([
            "children" => $children
        ]);
    }

    public function getNameEcole($id)
    {
        $nameSchool = Ecole::find($id);

        return response()->json([
            "name" => $nameSchool
        ]);
    }

    public function getNameClasse($id)
    {
        $nameClass = Classe::find($id);

        return response()->json([
            "name" => $nameClass
        ]);
    }

    public function getNameRole($id)
    {
        $nameRole = Role::find($id);

        return response()->json([
            "name" => $nameRole
        ]);
    }
}
