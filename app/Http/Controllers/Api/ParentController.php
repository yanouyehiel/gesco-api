<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\Role;
use App\Models\Cours;
use App\Models\Ecole;
use App\Models\Classe;
use App\Models\Devoir;
use App\Models\Absence;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ParentController extends Controller
{
    public function getNotesOfChildren(Request $request)
    {
        $notes = Note::all()
            ->where('student_id', auth()->user()->student_id);

        return response()->json([
            "notes" => $notes
        ]);
    }

    public function getAbsencesOfChildren(Request $request)
    {
        $absences = Absence::all()
            ->where('student_id', auth()->user()->student_id);

        return response()->json([
            "absences" => $absences
        ]);
    }

    public function getDevoirsOfChildren(Request $request)
    {
        $devoirs = Devoir::all()
            ->where('classe_id', auth()->user()->classe_id);

        return response()->json([
            "devoirs" => $devoirs
        ]);
    }

    public function getCoursOfChildren(Request $request)
    {
        $cours = Cours::all()
            ->where('classe_id', auth()->user()->classe_id);

        return response()->json([
            "cours" => $cours
        ]);
    }

    public function getInfoOfChildren(Request $request)
    {
        $children = Student::all()
            ->where('id', auth()->user()->student_id);

        return response()->json([
            "children" => $children
        ]);
    }

    public function getNameEcole(Request $request, $id)
    {
        $nameSchool = Ecole::all()
            ->where('id', $id);

        return response()->json([
            "name" => $nameSchool
        ]);
    }

    public function getNameClasse(Request $request, $id)
    {
        $nameClass = Classe::all()
            ->where('id', $id);

        return response()->json([
            "name" => $nameClass
        ]);
    }

    public function getNameRole(Request $request, $id)
    {
        $nameRole = Role::all()
            ->where('id', $id);

        return response()->json([
            "name" => $nameRole
        ]);
    }
}
