<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Models\Cours;
use App\Models\Devoir;
use App\Models\Absence;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class  TeacherController extends Controller
{
    public function getAllTeachers($ecole_id)
    {
        $teachers = DB::table('users')
            ->where('users.ecole_id', '=', $ecole_id)
            ->where('users.role_id', '=', 2)
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

        return response()->json($teacher[0], 200);
    }

    public function getStudentsOfClasseWeb($classe_id, $ecole_id)
    {
        $students = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('students.*', 'classes.nom as nom_classe')
            ->where('students.ecole_id', '=', $ecole_id)
            ->where('students.classe_id', '=', $classe_id)
            ->get();

        return response()->json($students, 200);
    }

    public function getStudentsOfClasse($classe_id, $ecole_id)
    {
        $students = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->join('users', 'students.parent_id', '=', 'users.id')
            ->select('students.*', 'classes.nom as nom_classe', 'users.nom as nom_parent', 'users.prenom as prenom_parent', 'users.telephone as tel_parent')
            ->where('students.ecole_id', '=', $ecole_id)
            ->where('students.classe_id', '=', $classe_id)
            ->get();

        return response()->json($students, 200);
    }

    public function getNotesStudents($ecole_id)
    {
        $notes = DB::table('notes')
            ->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
            ->join('students', 'notes.student_id', '=', 'students.id')
            ->join('classes', 'notes.classe_id', '=', 'classes.id')
            ->select('notes.*', 'matieres.intitule as nom_matiere', 'students.nom as nom_student', 'students.prenom as prenom_student', 'classes.nom as nom_classe')
            ->where('notes.ecole_id', (int) $ecole_id)
            ->orderByDesc('notes.created_at')
            ->get();
            
        return response()->json($notes, 200);
    }

    public function getNotesOfClasse(int $classe_id)
    {
        $classe = Classe::find($classe_id);
        
        $notes = DB::table('notes')
            ->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
            ->join('students', 'notes.student_id', '=', 'students.id')
            ->select('notes.*', 'matieres.intitule as nom_matiere', 'students.nom as nom_student', 'students.prenom as prenom_student')
            ->where('notes.ecole_id', (int) $classe->ecole_id)
            ->where('notes.classe_id', (int) $classe_id)
            ->orderByDesc('notes.created_at')
            ->get();
        
        return response()->json([
            'classe' => $classe,
            'notes' => $notes
        ], 200);
    }

    public function getNoteStudentById($student_id)
    {
        $note = Note::where('student_id', $student_id)->get();
        return response()->json($note, 200);
    }

    public function getAbsencesStudents($ecole_id)
    {
        $absences = DB::table('absences')
            ->join('students', 'absences.student_id', '=', 'students.id')
            ->join('classes', 'absences.classe_id', '=', 'classes.id')
            ->select('absences.*', 'students.nom as nom_student', 'students.prenom as prenom_student', 'classes.nom as nom_classe')
            ->where('absences.ecole_id', $ecole_id)
            ->orderByDesc('absences.created_at')
            ->get();
            
        return response()->json($absences, 200);
    }

    public function getAbsencesOfClasse($classe_id)
    {
        $absences = DB::table('absences')
            ->join('students', 'absences.student_id', '=', 'students.id')
            ->join('classes', 'absences.classe_id', '=', 'classes.id')
            ->select('absences.*', 'students.nom as nom_student', 'students.prenom as prenom_student', 'classes.nom as nom_classe')
            ->where('absences.classe_id', (int) $classe_id)
            ->orderByDesc('absences.created_at')
            ->get();
        return response()->json($absences, 200);
    }

    public function getDevoirsStudents($ecole_id)
    {
        $devoirs = DB::table('devoirs')
            ->join('livres', 'devoirs.livre_id', '=', 'livres.id')
            ->join('matieres', 'devoirs.matiere_id', '=', 'matieres.id')
            ->join('classes', 'devoirs.classe_id', '=', 'classes.id')
            ->select('devoirs.*', 'livres.intitule as nom_livre', 'matieres.intitule as nom_matiere', 'classes.nom as nom_classe')
            ->where('devoirs.ecole_id', (int) $ecole_id)
            ->orderByDesc('devoirs.created_at')
            ->get();
        
        return response()->json($devoirs, 200);
    }

    public function getDevoirsOfClasse($classe_id)
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

    public function getCoursStudents($ecole_id)
    {
        $cours = DB::table('cours')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->join('users', 'cours.teacher_id', '=', 'users.id')
            ->select('cours.*', 'matieres.intitule as nom_matiere', 'users.nom as nom_teacher', 'users.prenom as prenom_teacher')
            ->where('cours.ecole_id', (int) $ecole_id)
            ->orderByDesc('created_at')
            ->get();
            
        return response()->json($cours, 200); 
    }

    public function getCoursOfClasse($classe_id)
    {
        $cours = DB::table('cours')
            ->join('matieres', 'cours.matiere_id', '=', 'matieres.id')
            ->join('users', 'cours.teacher_id', '=', 'users.id')
            ->select('cours.*', 'matieres.intitule as nom_matiere', 'users.nom as nom_teacher', 'users.prenom as prenom_teacher')
            ->where('cours.classe_id', (int) $classe_id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'cours' => $cours
        ], 200); 
    }

    public function getMyClasses($teacher_id)
    {
        $classes = Classe::where('teacher_id', $teacher_id)->get();

        return response()->json($classes, 200);
    }

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
            'student_id' => 'required|integer',
            'matiere_id' => 'required|integer',
            'note' => 'required',
            'classe_id' => 'required|integer',
            'sequence' => 'required|integer',
            'ecole_id' => 'required|integer'
        ]);

        $note = new Note();
        $note->student_id = $request->student_id;
        $note->note = $request->note;
        $note->matiere_id = $request->matiere_id;
        $note->sequence = $request->sequence;
        $note->classe_id = $request->classe_id;
        $note->ecole_id = $request->ecole_id;
        $note->annee_scolaire = "2024-2025";
        $note->updated_at = now();
        $note->created_at = now();
        $note->save();

        return response()->json([
            'message' => 'La note a été enregistré avec succès.',
            'note' => $note
        ], 200);
    }
    
    public function updateNote(Request $request)
    {
        $note = Note::find((int) $request->id);
        $note->note = $request->note;
        $note->update();

        return response()->json([
            'message' => 'Note a bien été modifié'
        ], 200);
    }

    public function updateAbsenceStudent(Request $request)
    {
        $absence = new Absence();
        $absence->student_id = $request->student_id;
        $absence->periode = $request->periode;
        $absence->user_id = auth()->user()->id;
        $absence->update();

        return response($absence, Response::HTTP_CREATED);
    }

    public function addAbsenceStudent(Request $request)
    {
        $request->validate([
            'periode' => 'required',
            'student_id' => 'required',
            'ecole_id' => 'required',
            'classe_id' => 'required'
        ]);

        $absence = new Absence();
        $absence->student_id = (int) $request->student_id;
        $absence->periode = $request->periode;
        $absence->ecole_id = (int) $request->ecole_id;
        $absence->classe_id = (int) $request->classe_id;
        $absence->annee_scolaire = "2024-2025";
        $absence->updated_at = now();
        $absence->created_at = $request->date;
        $absence->save();

        return response()->json([
            'message' => "Absence enregistrée avec succès !",
            'absence' => $absence
        ], 200);
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
            'num_exo' => 'required',
            'ecole_id' => 'required',
            'classe_id' => 'required',
            'matiere_id' => 'required'
        ]);

        $devoir = new Devoir();
        $devoir->livre_id = (int) $request->livre_id;
        $devoir->num_page = (int) $request->num_page;
        $devoir->num_exo = (int) $request->num_exo;
        $devoir->classe_id = (int) $request->classe_id;
        $devoir->ecole_id = (int) $request->ecole_id;
        $devoir->matiere_id = (int) $request->matiere_id;
        $devoir->annee_scolaire = "2024-2025";
        $devoir->updated_at = now();
        $devoir->created_at = now();
        $devoir->save();

        return response()->json([
            'message' => 'Devoir enregistré avec succès',
            'devoir' => $devoir
        ], 200);
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
            'matiere_id' => 'required',
            'classe_id' => 'required',
            'teacher_id' => 'required',
            'titre' => 'required',
            'description' => 'required'
        ]);

        $cours = new Cours();
        $cours->titre = $request->titre;
        $cours->description = $request->description;
        $cours->classe_id = (int) $request->classe_id;
        $cours->ecole_id = (int) $request->ecole_id;
        $cours->matiere_id = (int) $request->matiere_id;
        $cours->teacher_id = $request->teacher_id ? (int) $request->teacher_id : Auth::user()->id;
        $cours->annee_scolaire = "2024-2025";
        $cours->updated_at = now();
        $cours->created_at = now();
        $cours->save();

        return response()->json([
            'message' => "Cours enregistré avec succès !",
            'cours' => $cours
        ], 200);
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
