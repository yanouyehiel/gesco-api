<?php

use App\Models\TypeClasse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Middleware\CheckEcole;
use App\Http\Middleware\AddCustomHeaders;

Route::middleware(['auth:sanctum', CheckEcole::class, AddCustomHeaders::class])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/user/{id}', [AuthController::class, 'userProfile']);
    Route::get('/users', [AuthController::class, 'allUsers']);
    Route::get('/my-students/classe_id={classe_id}&ecole_id={ecole_id}', [TeacherController::class, 'getStudentsOfClasse']); //Lister tous les eleves d'un classe
    Route::get('/students/classe_id={classe_id}&ecole_id={ecole_id}', [TeacherController::class, 'getStudentsOfClasseWeb']);
    Route::get('/notes-students/{id}', [TeacherController::class, 'getNotesStudents']); //Affichage de toutes les notes des élèves d'une école toute classe confondue
    Route::get('/get-notes-classe/{id}', [TeacherController::class, 'getNotesOfClasse']); //Affichage des notes des élèves d'une classe précise
    Route::get('/note-student/{id}', [TeacherController::class, 'getNoteStudentById']); //Notes d'un élève
    Route::get('/absences-students/{id}', [TeacherController::class, 'getAbsencesStudents']); //Liste de toutes les absences des élèves d'une école
    Route::get('/devoirs-students/{id}', [TeacherController::class, 'getDevoirsStudents']); //Lister tous les devoirs d'une école
    Route::get('/devoirs-classe/{id}', [TeacherController::class, 'getDevoirsOfClasse']); //Lister tous les devoirs d'une classe
    //Route::get('/devoir-student/{id}', [TeacherController::class, 'getDevoirsOfStudent']);
    Route::get('/cours-students/{id}', [TeacherController::class, 'getCoursStudents']); //Liste des cours d'une école
    Route::get('/get-cours-classe/{id}', [TeacherController::class, 'getCoursOfClasse']); //Liste des cours d'une classe
    Route::get('/get-notes-children/{id}', [ParentController::class, 'getNotesOfChildren']);
    Route::get('/get-absences-children/{id}', [ParentController::class, 'getAbsencesOfChildren']);
    Route::get('/get-absences-classe/{id}', [TeacherController::class, 'getAbsencesOfClasse']);
    Route::get('/get-absences/{id}', [TeacherController::class, 'getAbsencesStudents']);
    Route::get('/get-devoirs-children/{id}', [ParentController::class, 'getDevoirsOfChildren']);
    Route::get('/get-cours-children/{id}', [ParentController::class, 'getCoursOfChildren']);
    Route::get('/get-my-children/{id}', [ParentController::class, 'getMyAllChildren']);
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
    Route::get('/get-all-students/{id}', [MainController::class, 'getAllStudents']);
    Route::get('/get-student/{id}', [MainController::class, 'getStudent']); //Information sur un élève
    Route::get('/get-parents/{id}', [MainController::class, 'getParents']); //Lister tous les parents d'élèves d'une école
    Route::get('/get-parent/{id}', [MainController::class, 'getParent']);
    Route::get('/get-tarifs/{id}', [MainController::class, 'getTarifs']); //Lister le coût des pensions de toutes les classes d'un établissement
    Route::get('/get-paiements/{id}', [MainController::class, 'getPaiements']); //Lister tous les paiements de pension
    Route::get('/get-fees-student/{student}', [MainController::class, 'getFeesStudent']); //Lister tous les paiements d'un eleve
    Route::get('/get-somme-paye/{id}', [MainController::class, 'getSommePaye']); //Pensin payé par un étudiant
    Route::get('/get-documents-asked/{id}', [MainController::class, 'getDocumentsAsked']);
    Route::get('/get-messages/{id}/{idUser}', [MainController::class, 'getMessages']); //Recupere les messages d'une ecole
    Route::get('/get-events/{id}', [MainController::class, 'getEvents']); //Recupere tous les events d'une ecole
    Route::get('/get-calendars/{id}', [MainController::class, 'getCalendars']); //Recupere tous les calendriers d'une ecole
    Route::get('/get-horaires/{id}', [MainController::class, 'getHoraires']);
    Route::get('/get-user/{id}', [MainController::class, 'getUser']);
    Route::get('/my-classes/{id}', [TeacherController::class, 'getMyClasses']);
    Route::get('/get-livres/{id}', [MainController::class, 'getLivres']);
    Route::get('/get-fees-ecole/{id}', [MainController::class, 'getFeesEcole']);
    Route::get('/get-tokens', [MainController::class, 'getTokens']);
    Route::get('/get-school/{id}', [MainController::class, 'getSchool']);

    Route::post('/add-matiere', [MainController::class, 'addMatiere']); //Ajouter une matière
    Route::post('/add-note', [TeacherController::class, 'addNoteStudent']); //Ajouter une note
    Route::post('/add-absence', [TeacherController::class, 'addAbsenceStudent']); //Ajouter une absence
    Route::post('/add-devoir', [TeacherController::class, 'addDevoirStudent']); //Ajouter un devoir
    Route::post('/add-cours', [TeacherController::class, 'addCoursStudent']); //Ajouter un cours
    Route::post('/add-classe', [MainController::class, 'addClasse']); //Ajouter une classe
    Route::post('/add-tarif', [MainController::class, 'addTarif']); //AJouter un tarif de pension d'une classe
    Route::post('/add-paiement', [MainController::class, 'addPaiement']); //AJouter un paiement de pension
    Route::post('/add-student', [MainController::class, 'addStudent']); //Creer un nouvel eleve
    Route::post('/ask-document', [MainController::class, 'askDocument']); //Demander un document
    Route::post('/add-event', [MainController::class, 'addEvent']); //Enregistrer un evenement
    Route::post('/add-calendar', [MainController::class, 'addCalendar']); //Enregistrer un calendrier
    Route::post('/add-horaire', [MainController::class, 'addHoraire']); //Enregistrer une tranche horaire
    Route::post('/add-livre', [MainController::class, 'addLivre']); //Enregistrer un livre
    Route::post('/add-message', [MainController::class, 'addMessage']); //Enregistrer un message
    Route::post('/add-personne', [AuthController::class, 'addPersonne']); //Inscription d'un directeur, d'une secretaire, d'un maitre et d'un parent
    Route::post('/auth/register', RegisterController::class); //Inscription
    Route::post('/auth/logout', LogoutController::class); //Déconnexion
    Route::post('/import-list-students', [ImportController::class, 'importListStudents']); //Importer la liste des élèves
    Route::post('/link-student-parent', [MainController::class, 'linkStudentToParent']);

    //Update Routes
    Route::put('/update-calendar', [MainController::class, 'updateCalendar']); //Modifier un calendrier
    Route::put('/update-user', [UserController::class, 'updateUser']); //Modifier le profil d'un user
    Route::put('/update-message', [MainController::class, 'updateMessage']);
    Route::put('/update-tarif', [MainController::class, 'updateTarif']);
    Route::put('/update-classe', [MainController::class, 'updateClasse']);
    Route::put('/update-event', [MainController::class, 'updateEvent']);
    Route::put('/validate-request', [MainController::class, 'validateRequest']); //Valider une requete de document
    Route::put('/update-employe', [MainController::class, 'updateEmploye']);
    Route::put('/update-note', [TeacherController::class, 'updateNoteStudent']); //Modifier la note sur mobile
    
    //Delete Routes
    Route::delete('/delete-classe/{id}', [MainController::class, 'deleteClasse']); //Supprimer une classe
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']); //Supprimer un maitre
    Route::delete('/delete-calendar/{id}', [MainController::class, 'deleteCalendar']); //Supprimer un calendrier
    Route::delete('/delete-livre/{id}', [MainController::class, 'deleteLivre']); //Supprimer un livre
});

Route::middleware([AddCustomHeaders::class])->group(function () {
    //Get Routes
    Route::get('/get-types-etablissement', [MainController::class, 'getTypesEtablissement']);
    Route::get('/get-all-school', [MainController::class, 'getEcoles']);
    Route::get('/users', [AuthController::class, 'allUsers']);

    //Post Routes  
    Route::post('/auth/register', RegisterController::class); //Inscription
    Route::post('/register', [AuthController::class, 'addPersonne']); //Inscription
    Route::post('/register-mobile', [AuthController::class, 'addPersonneFromMobile']); //Inscription
    Route::post('/add-ecole', [MainController::class, 'addEcole']); //AJouter une ecole
});

Route::middleware([AddCustomHeaders::class, CheckEcole::class])->group(function () {
    Route::post('/auth/login', LoginController::class); //Se connecter
    Route::post('/auth/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.send');
    Route::post('/auth/password/reset/{email}/{expires}/{signature}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/auth/email/verify/send', [VerifyEmailController::class, 'sendMail']);
    Route::post('/auth/email/verify/{email}/{expires}/{signature}', [VerifyEmailController::class, 'verify'])->name('verify-email');
});