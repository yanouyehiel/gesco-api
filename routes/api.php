<?php

use App\Models\TypeClasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\ParentController;
use App\Http\Controllers\Api\TeacherController;

Route::get('/profile/{id}', [AuthController::class, 'userProfile']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/users', [AuthController::class, 'allUsers']);
Route::get('/my-students/classe_id={classe_id}&ecole_id={ecole_id}', [TeacherController::class, 'getStudentsOfTeacher']); //Lister tous les eleves d'un classe
Route::get('/notes-students/{id}', [TeacherController::class, 'getNotesStudents']); //Affichage de toutes les notes des élèves d'une école toute classe confondue
Route::get('/notes-classe/{id}', [TeacherController::class, 'getNotesOfClasse']); //Affichage des notes des élèves d'une classe précise
Route::get('/note-student/{id}', [TeacherController::class, 'getNoteStudentById']); //Notes d'un élève
Route::get('/absences-students/{id}', [TeacherController::class, 'getAbsencesStudents']); //Liste de toutes les absences des élèves d'une école
Route::get('/absences-classe/{id}', [TeacherController::class, 'getAbsencesOfClasse']); //Liste de toutes les absences d'une classe
Route::get('/absence-student/{id}', [TeacherController::class, 'getAbsenceStudentById']); //Absences d'un élève
Route::get('/devoirs-students/{id}', [TeacherController::class, 'getDevoirsStudents']); //Lister tous les devoirs d'une école
Route::get('/devoirs-classe/{id}', [TeacherController::class, 'getDevoirsOfClasse']); //Lister tous les devoirs d'une classe
//Route::get('/devoir-student/{id}', [TeacherController::class, 'getDevoirsOfStudent']);
Route::get('/cours-students/{id}', [TeacherController::class, 'getCoursStudents']); //Liste des cours d'une école
Route::get('/cours-classe/{id}', [TeacherController::class, 'getCoursOfClasse']); //Liste des cours d'une classe
Route::get('/get-notes-children', [ParentController::class, 'getNotesOfChildren']);
Route::get('/get-absences-children', [ParentController::class, 'getAbsencesOfChildren']);
Route::get('/get-devoirs-children', [ParentController::class, 'getDevoirsOfChildren']);
Route::get('/get-cours-children', [ParentController::class, 'getCoursOfChildren']);
Route::get('/get-profile-children', [ParentController::class, 'getInfoOfChildren']);
Route::get('/get-name-school/{id}', [ParentController::class, 'getNameEcole']);
Route::get('/get-roles', [MainController::class, 'roles']);
Route::get('/get-role/{id}', [MainController::class, 'getRole']);
Route::get('/get-types-classe', [MainController::class, 'getTypesClasse']);
Route::get('/get-type-classe/{id}', [MainController::class, 'getTypeClasse']);
Route::get('/get-classes-school/{id}', [MainController::class, 'getClassesSchool']); //Lister toutes les classes d'une ecole
Route::get('/get-info-classe/{id}', [MainController::class, 'getInfoClasse']); //
Route::get('/get-personnel/{id}', [MainController::class, 'getEmployesOfSchool']); //Liste de tous les employes d'une ecole
Route::get('/get-teachers/{id}', [TeacherController::class, 'getAllTeachers']); //Lister tous les maitres d'une ecole
Route::get('/get-teacher/{id}', [TeacherController::class, 'getTeacher']); //Information sur un maitre
Route::get('/profile-teacher/{id}', [TeacherController::class, 'profileTeacher']); //Information sur un maitre
Route::get('/profile-employe/{id}', [MainController::class, 'profileEmploye']); //Information sur un employe
Route::get('/get-ecole/{id}', [MainController::class, 'getInfoEcole']); //Information sur une ecole
Route::get('/get-matieres/{id}', [MainController::class, 'getMatieres']); //Liste de toutes les matières d'une école
Route::get('/get-students/{id}', [MainController::class, 'getStudents']); //Lister les élèves d'une école
Route::get('/get-student/{id}', [MainController::class, 'getStudent']); //Information sur un élève
Route::get('/get-parents/{id}', [MainController::class, 'getParents']); //Lister tous les parents d'élèves d'une école
Route::get('/get-parent/{id}', [MainController::class, 'getParent']);
Route::get('/get-types-etablissement', [MainController::class, 'getTypesEtablissement']);
Route::get('/get-tarifs/{id}', [MainController::class, 'getTarifs']); //Lister le coût des pensions de toutes les classes d'un établissement
Route::get('/get-paiements/{id}', [MainController::class, 'getPaiements']); //Lister tous les paiements de pension


//Post Routes
Route::post('/add-personne', [AuthController::class, 'addPersonne']); //Inscription d'un directeur, d'une secretaire, d'un maitre et d'un parent
Route::post('/login', [AuthController::class, 'login']); //Se connecter
Route::post('/register', [AuthController::class, 'addPersonne']); //Inscription
Route::post('/add-matiere', [MainController::class, 'addMatiere']); //Ajouter une matière
Route::post('/add-note', [TeacherController::class, 'addNoteStudent']); //Ajouter une note
Route::post('/add-absence', [TeacherController::class, 'addAbsenceStudent']); //Ajouter une absence
Route::post('/add-devoir', [TeacherController::class, 'addDevoirStudent']); //Ajouter un devoir
Route::post('/add-cours', [TeacherController::class, 'addCoursStudent']); //Ajouter un cours
Route::post('/add-classe', [MainController::class, 'addClasse']); //Ajouter une classe
Route::post('/add-ecole', [MainController::class, 'addEcole']); //AJouter une ecole
Route::post('/add-tarif', [MainController::class, 'addTarif']); //AJouter un tarif de pension d'une classe
Route::post('/add-paiement', [MainController::class, 'addPaiement']); //AJouter un paiement de pension


//Delete Routes
Route::delete('/delete-classe/{id}', [MainController::class, 'deleteClasse']); //Supprimer une classe
Route::get('/delete-user/{id}', [UserController::class, 'deleteUser']); //Supprimer un maitre

//Admin route
/*Route::middleware(['auth', 'user-role:user'])->group(function() {
    Route::get("/home", [HomeController::class, 'adminHome'])->name('home');
});

//Teacher Route
Route::middleware(['auth', 'user-role:teacher'])->group(function() {
    Route::get("/teacher/home", [HomeController::class, 'teacherHome'])->name('home');
});

//Parent Route
Route::middleware(['auth', 'user-role:parent'])->group(function() {
    Route::get("/parent/home", [HomeController::class, 'parentHome'])->name('home');
});*/
