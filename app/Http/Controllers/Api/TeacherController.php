<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\User;
use App\Models\Cours;
use App\Models\Devoir;
use App\Models\Absence;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function getAllTeachers($ecole_id)
    {
        $teachers = DB::table('users')
            ->join('classes', 'users.classe_id', '=', 'classes.id')
            ->select('users.*', 'classes.nom as nom_classe')
            ->where('users.ecole_id', '=', $ecole_id)
            ->where('users.role_id', 3)
            ->get();

        return response()->json($teachers, 200);
    }

    public function getTeacher($id)
    {
        $teacher = DB::table('users')
            ->join('classes', 'users.classe_id', '=', 'classes.id')
            ->select('users.*', 'classes.nom as nom_classe', 'classes.effectif')
            ->where('users.id', $id)
            ->get();

        return response()->json($teacher, 200);
    }

    public function getStudentsOfTeacher($classe_id, $ecole_id)
    {
        $students = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('students.*', 'classes.nom as nom_classe')
            ->where('students.ecole_id', '=', $ecole_id)
            ->where('students.classe_id', '=', $classe_id)
            ->get();

        return response()->json($students, 200);
    }

    public function getNotesStudents($ecole_id)
    {
        $notes = Note::where('ecole_id', $ecole_id)->get();
        return response()->json($notes, 200);
    }

    public function getNotesOfClasse($user_id)
    {
        $notes = Note::where('user_id', $user_id)->get();
        return response()->json($notes, 200);
    }

    public function getNoteStudentById($student_id)
    {
        $note = Note::where('student_id', $student_id)->get();
        return response()->json($note, 200);
    }

    public function getAbsencesStudents($ecole_id)
    {
        $absences = Absence::where('ecole_id', $ecole_id)->get();
        return response()->json($absences, 200);
    }

    public function getAbsencesOfClasse($user_id)
    {
        $absences = Absence::where('user_id', $user_id)->get();
        return response()->json($absences, 200);
    }

    public function getAbsenceStudentById($student_id)
    {
        $absences = Absence::where('student_id', $student_id)->get();
        return response()->json($absences, 200);
    }

    public function getDevoirsStudents($ecole_id)
    {
        $devoirs = Devoir::where('ecole_id', $ecole_id)->get();
        return response()->json($devoirs, 200);
    }

    public function getDevoirsOfClasse($classe_id)
    {
        $devoirs = Devoir::where('classe_id', $classe_id)->get();
        return response()->json($devoirs, 200);
    }

    /*public function getDevoirsOfStudent($student_id)
    {
        $devoir = Student::where('id', $student_id)->get();
        return response()->json($devoir->absences, 200);
    }*/

    public function getCoursStudents($ecole_id)
    {
        $cours = Cours::where('ecole_id', $ecole_id)->get();
        return response()->json($cours, 200); 
    }

    public function getCoursOfClasse($classe_id)
    {
        $cours = Cours::where('classe_id', $classe_id)->get();
        return response()->json($cours, 200); 
    }

    /*public function getCourStudentById(Request $request, $id)
    {
        $cours = Devoir::all()
            ->where('id', $id)
            ->where('user_id', auth()->user()->id);
        return response()->json([
            "cours" => $cours
        ]);
    }*/

    public function updateNoteStudent(Request $request)
    {
        $note = new Note();
        $note->student_id = $request->student_id;
        $note->note = $request->note;
        $note->matiere_id = $request->matiere_id;
        $note->user_id = auth()->user()->id;
        $note->save();

        return response($note, Response::HTTP_CREATED);
    }

    public function addNoteStudent(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'matiere_id' => 'required',
            'note' => 'required',
            'user_id' => 'required',
            'ecole_id' => 'required'
        ]);

        $note = new Note();
        $note->student_id = $request->student_id;
        $note->note = $request->note;
        $note->matiere_id = $request->matiere_id;
        $note->user_id = $request->user_id;
        $note->ecole_id = $request->ecole_id;
        $note->updated_at = now();
        $note->created_at = now();
        $note->save();

        return response($note, Response::HTTP_CREATED);
    }

    public function updateAbsenceStudent(Request $request)
    {
        $absence = new Absence();
        $absence->student_id = $request->student_id;
        $absence->periode = $request->periode;
        $absence->user_id = auth()->user()->id;
        $absence->save();

        return response($absence, Response::HTTP_CREATED);
    }

    public function addAbsenceStudent(Request $request)
    {
        $request->validate([
            'periode' => 'required',
            'student_id' => 'required'
        ]);

        $absence = new Absence();
        $absence->student_id = $request->student_id;
        $absence->periode = $request->periode;
        $absence->user_id = $request->user_id;
        $absence->ecole_id = $request->ecole_id;
        $absence->updated_at = now();
        $absence->created_at = now();
        $absence->save();

        return response($absence, Response::HTTP_CREATED);
    }

    public function updateDevoirStudent(Request $request)
    {
        $devoir = new Devoir();
        $devoir->livre_id = $request->livre_id;
        $devoir->num_page = $request->num_page;
        $devoir->num_exo = $request->num_exo;
        $devoir->classe_id = auth()->user()->classe_id;
        $devoir->save();

        return response($devoir, Response::HTTP_CREATED);
    }

    public function addDevoirStudent(Request $request)
    {
        $request->validate([
            'livre_id' => 'required',
            'num_page' => 'required',
            'num_exo' => 'required'
        ]);

        $devoir = new Devoir();
        $devoir->livre_id = $request->livre_id;
        $devoir->num_page = $request->num_page;
        $devoir->num_exo = $request->num_exo;
        $devoir->classe_id = $request->classe_id;
        $devoir->updated_at = now();
        $devoir->created_at = now();
        $devoir->save();

        return response($devoir, Response::HTTP_CREATED);
    }

    public function updateCourStudent(Request $request)
    {
        $cours = new Cours();
        $cours->titre = $request->titre;
        $cours->description = $request->description;
        $cours->classe_id = auth()->user()->classe_id;
        $cours->save();

        return response()->json($cours, Response::HTTP_CREATED);
    }

    public function addCoursStudent(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'description' => 'required'
        ]);

        $cours = new Cours();
        $cours->titre = $request->titre;
        $cours->description = $request->description;
        $cours->classe_id = $request->classe_id;
        $cours->ecole_id = $request->ecole_id;
        $cours->updated_at = now();
        $cours->created_at = now();
        $cours->save();

        return response()->json($cours, Response::HTTP_CREATED);
    }

    public function profileTeacher(int $classe_id)
    {
        $teacher = User::all()
            ->where('role_id', 3)
            ->where('classe_id', $classe_id);

        return response()->json($teacher, 200);
    }

    public function getTeacherByClasse(int $id_classe)
    {
        $teacher = User::all()
            ->where('classe_id', $id_classe)
            ->where('role_id', 2);

        return response()->json($teacher, 200);
    }
}
