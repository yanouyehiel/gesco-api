<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Ecole;
use App\Models\Matiere;
use App\Models\Classe;
use App\Models\TypeClasse;
use App\Models\Tarif;
use App\Models\TypeEtablissement;
use App\Models\Student;
use App\Models\Paiement;
use App\Models\Notification;
use App\Models\Message;
use App\Models\CoefficientMatiere;
use App\Models\GroupeMatiere;
use App\Models\Event;
use App\Models\Livre;
use App\Models\Note;
use App\Models\Calendar;
use App\Models\TrancheHoraire;
use App\Models\Trimestre;
use App\Models\Sequence; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\EmailEcoleRegistred;
use Illuminate\Support\Facades\Mail;

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

    public function getCycles(int $ecole_id)
    {
        $cycles = Cycle::all();
        return response()->json($cycles, 200);
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
        $matieres = DB::table('matieres')
            ->join('groupe_matieres', 'matieres.groupe_matiere_id', '=', 'groupe_matieres.id')
            ->select("matieres.*", 'groupe_matieres.intitule as nom_groupe')
            ->where('matieres.ecole_id', $ecole_id)
            ->get();
        
        return response()->json($matieres, 200);
    }

    public function getSingleMatiere(int $id)
    {
        $matiere = Matiere::find($id);
        $coefficients = DB::table('coefficient_matieres')
            ->join('classes', 'coefficient_matieres.classe_id', '=', 'classes.id')
            ->select('coefficient_matieres.*', 'classes.nom as nom_classe')
            ->where('coefficient_matieres.matiere_id', $matiere->id)
            ->get();

        return response()->json([
            'matiere' => $matiere,
            'coefficients' => $coefficients
        ], 200);
    }

    public function addClasse(Request $req)
    {
        $classe = new Classe();
        $classe->nom = $req->nom;
        $classe->ecole_id = (int) $req->ecole_id;
        $classe->type_classe_id = (int) $req->type_classe_id;
        $classe->effectif = 0;
        $classe->teacher_id = isset($req->teacher_id) ? $req->teacher_id : null;
        $classe->cycle_id = isset($req->cycle_id) ? $req->cycle_id : null;
        $classe->created_at = now();
        $classe->updated_at = now();
        $classe->save();

        return response()->json([
            'message' => 'Classe enregistrée avec succès !',
            'data' => $classe
        ], 200);
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
            ->join('users', 'classes.teacher_id', '=', 'users.id')
            ->select('classes.*', 'users.nom as nom_teacher', 'users.prenom as prenom_teacher')
            ->where('classes.ecole_id', $ecole_id)
            ->get();

        return response()->json($classes, 200);
    }

    public function getInfoClasse($id)
    {
        $classe = Classe::find($id);
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

    public function getUser($id)
    {
        $user = User::find($id);

        return response()->json($user, Response::HTTP_ACCEPTED);
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
        $teachers = User::where('ecole_id', $id)->where('role_id', 2)->count();
        $parents = User::where('ecole_id', $id)->where('role_id', 3)->count();
        $students = Student::where('ecole_id', $id)->count();

        return response([
            'ecole' => $ecole,
            'teachers' => $teachers,
            'parents' => $parents,
            'students' => $students
        ]);
    }

    public function getTypesEtablissement()
    {
        $typesEtablissement = TypeEtablissement::all();

        return response()->json($typesEtablissement);
    }

    public function addEcole(Request $req)
    {
        $ecole = new Ecole();

        $ecole->nom = $req->nom;
        $ecole->pays = $req->pays;
        $ecole->localisation = $req->localisation;
        $ecole->ville = $req->ville;
        $ecole->telephone = $req->telephone;
        $ecole->email = $req->email;
        $ecole->site_web = $req->site_web;
        $ecole->type_etablissement_id = (int) $req->type_etablissement_id;
        $ecole->bloque = 1;
        $ecole->matricule = Str::random(9);
        $ecole->created_at = now();
        $ecole->updated_at = now();
        $ecole->save();

        Mail::to($req->email)->send(new EmailEcoleRegistred($ecole));

        return response()->json([
            'message' => 'Ecole créée avec succès !',
            'data' => $ecole
        ]);
    }

    public function getEcoles()
    {
        $ecoles = Ecole::all();

        return response()->json($ecoles, 200);
    }

    public function addMatiere(Request $req)
    {
        $matiere = new Matiere();
        $matiere->code = $req->code;
        $matiere->intitule = $req->intitule;
        $matiere->ecole_id = (int) $req->ecole_id;
        $matiere->groupe_matiere_id = (int) $req->groupe_matiere_id;
        $matiere->save();

        foreach ($req->coefficients as $key => $coeff) {
            $coefficent = new CoefficientMatiere();
            $coefficent->classe_id = (int) $coeff['classe_id'];
            $coefficent->coefficient = (int) $coeff['coefficient'];
            $coefficent->matiere_id = (int) $matiere->id;
            $coefficent->save();
        }

        return response()->json([
            'message' => "Matière et coefficient enregistés avec succès !",
            'data' => $matiere
        ], 200);
    }

    public function addGroupeMatiere(Request $req)
    {
        $groupe = new GroupeMatiere();
        $groupe->intitule = $req->intitule;
        $groupe->ecole_id = $req->ecole_id;
        $groupe->save();

        return response()->json([
            'message' => 'Groupe enregistré !',
            'data' => $groupe
        ], 200);
    }

    public function getGroupesMatiere(int $ecole_id)
    {
        $groupes = GroupeMatiere::where('ecole_id', $ecole_id)->get();
        return response()->json($groupes, 200);
    }

    public function getStudents($ecole_id)
    {
        $students = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->join('users', 'students.parent_id', '=', 'users.id')
            ->select('students.*', 'classes.nom as nom_classe', 'users.nom as nom_parent', 'users.prenom as prenom_parent')
            ->where('students.ecole_id', $ecole_id)
            ->get();

        $studentUnlinked = DB::table('students')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('students.*', 'classes.nom as nom_classe')
            ->where('students.ecole_id', $ecole_id)
            ->where('students.parent_id', null)
            ->get();

        return response()->json([
            'studentsLinked' => $students,
            'studentsUnlinked' => $studentUnlinked
        ], 200);
    }
    
    public function getAllStudents($ecole_id)
    {
        $students = Student::where('ecole_id', (int) $ecole_id)->get(); 
        return response()->json($students, 200);
    }

    public function getStudent($id)
    {
        $student = Student::find((int) $id);

        return response()->json([
            'student' => $student,
            'classe' => $student->classe,
            //'absence' => $student->absences
        ], 200);
    }

    public function getParents($ecole_id)
    {
        $parents = User::where('role_id', 3)
            ->where('ecole_id', (int) $ecole_id)
            ->get();

        return response()->json($parents, 200);
    }

    public function getParent($id)
    {
        $parent = DB::table('users')
            ->join('students', 'users.id', '=', 'students.parent_id')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            ->select('users.*', 'students.nom as nom_student', 'students.prenom as prenom_student', 'classes.nom as nom_classe')
            ->where('users.id', $id)
            ->get();

        return response()->json($parent[0], 200);
    }

    public function getTarifs($ecole_id)
    {
        $tarifs = DB::table('tarifs')
            ->join('type_classes', 'tarifs.type_classe_id', '=', 'type_classes.id')
            ->select('tarifs.*', 'type_classes.classe as classe')
            ->where('tarifs.ecole_id', $ecole_id)
            ->get();

        return response()->json($tarifs, 200);
    }

    public function addTarif(Request $req)
    {
        $exist = Tarif::where('type_classe_id', (int) $req->type_classe_id)->get();

        if (count($exist) > 0) {
            return response()->json([
                'message' => 'Le tarif de cette classe existe déjà'
            ], 500);
        } else {
            $classes = Classe::where('type_classe_id', (int) $req->type_classe_id)
                ->where('ecole_id', $req->ecole_id)
                ->get();

            if (count($classes) > 0) {
                $tarif = new Tarif();
                $tarif->type_classe_id = (int) $req->type_classe_id;
                $tarif->inscription = (int) $req->inscription;
                $tarif->premiere_tranche = (int) $req->premiere_tranche;
                $tarif->deuxieme_tranche = (int) $req->deuxieme_tranche;
                $tarif->troisieme_tranche = (int) $req->troisieme_tranche;
                $tarif->ecole_id = (int) $req->ecole_id;
                $tarif->created_at = now();
                $tarif->updated_at = now();
                $tarif->save();

                return response([
                    'message' => 'Tarif enregistré avec succès !',
                    'data' => $tarif
                ]);
            } else {
                $typeClasse = TypeClasse::where('id', (int) $req->type_classe_id)->get();
                return response()->json([
                    'message' => "Veuillez d'abord créer la classe ".$typeClasse[0]->classe
                ], 500);
            }
        }
        
    }

    public function resumeFinanceStudent($student_id)
    {
        $student = Student::find((int) $student_id);

        $tarifs = DB::table('tarifs')
            ->join('classes', 'tarifs.type_classe_id', '=', 'classes.type_classe_id')
            ->select('tarifs.*')
            ->where('classes.id', $student->classe_id)
            ->where('tarifs.ecole_id', $student->ecole_id)
            ->get();

        $paiements = Paiement::where('student_id', (int) $student_id)->get();
        $sommePaye = (int) $paiements->sum('montant');

        $totalPension = $tarifs[0]->inscription 
            + $tarifs[0]->premiere_tranche 
            + $tarifs[0]->deuxieme_tranche 
            + $tarifs[0]->troisieme_tranche;

        return response([
            'total' => $totalPension,
            'paye' => $sommePaye,
            'reste' => $totalPension - $sommePaye
        ]);
    }

    public function addPaiement(Request $req)
    {
        $student_id = (int) $req->student_id;
        $response = $this->resumeFinanceStudent($student_id);
        $resume = $response->original;

        if ($resume['reste'] == 0) {
            return response([
                'status_code' => 500,
                'message' => "Frais de pension totalement payés"
            ]);
        } else {
            if ($resume['reste'] < $req->montant) {
                return response([
                    'status_code' => 500,
                    'message' => "Il ne reste plus qu'à payer $resume[reste] FCFA"
                ]);
            } else {
                $paiement = new Paiement();
                $paiement->code = Str::random(10);
                $paiement->intitule = $req->intitule;
                $paiement->montant = (int) $req->montant;
                $paiement->created_at = now();
                $paiement->updated_at = now();
                $paiement->student_id = $student_id;
                $paiement->ecole_id = (int) $req->ecole_id;
                $paiement->annee_scolaire = "2024-2025";
                $paiement->save();

                return response([
                    'message' => 'Paiement enregistré avec succès !',
                    'data' => $paiement
                ]);
            }
        }
    }

    public function getPaiements($ecole_id)
    {
        $paiements = DB::table('paiements')
            ->join('students', 'paiements.student_id', '=', 'students.id')
            ->join('classes', 'students.classe_id', '=', 'classes.id')
            //->join('tarifs', 'classes.id', '=', 'tarifs.classe_id')
            ->select('paiements.*', 'students.nom as nom_student', 'students.prenom as prenom_student')
            //->select('tarifs.*', 'classes.nom as nom_classe')
            ->where('paiements.ecole_id', $ecole_id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($paiements, 200);
    }

    public function getPaiementsChart($ecole_id)
    {
        $paiements = Paiement::where('ecole_id', (int) $ecole_id)
            ->get();

        return response()->json($paiements, 200);
    }

    public function getFeesStudent($student_id)
    {
        $student = Student::find((int) $student_id);

        $tarifs = DB::table('tarifs')
            ->join('classes', 'tarifs.type_classe_id', '=', 'classes.type_classe_id')
            ->select('tarifs.*')
            ->where('classes.id', $student->classe_id)
            ->where('tarifs.ecole_id', $student->ecole_id)
            ->get();

        $paiements = Paiement::where('student_id', (int) $student_id)->get();
        $sommePaye = (int) $paiements->sum('montant');

        $totalPension = $tarifs[0]->inscription 
            + $tarifs[0]->premiere_tranche 
            + $tarifs[0]->deuxieme_tranche 
            + $tarifs[0]->troisieme_tranche;

        return response([
            'paiements' => $paiements,
            'total' => $totalPension,
            'paye' => $sommePaye,
            'reste' => $totalPension - $sommePaye,
            'tarifs' => $tarifs[0]
        ]);
    }

    public function addStudent(Request $req)
    {
        $student = new Student();

        $student->matricule = strtoupper(Str::random(12));
        $student->nom = $req->nom;
        $student->prenom = $req->prenom;
        $student->date_naissance = $req->date_naissance;
        $student->lieu_naissance = $req->lieu_naissance;
        $student->date_scolarisation = $req->annee_scolaire;
        $student->sexe = $req->sexe;
        $student->classe_id = (int) $req->classe_id;
        $student->parent_id = isset($req->parent_id) ? (int) $req->parent_id : null;
        $student->ecole_id = (int) $req->ecole_id;
        $student->created_at = now();
        $student->updated_at = now();

        $student->save();

        $classe = Classe::find((int) $req->classe_id);
        $classe->effectif = $classe->effectif + 1;
        $classe->update();

        return response()->json([
            'message' => 'Elève enregistré.',
            'data' => [
                'student' => $student,
                'classe' => $classe
            ],
        ], 200);
    }

    public function askDocument(Request $req)
    {
        $notif = new Notification();
        $notif->ecole_id = (int) $req->ecole_id;
        $notif->intitule = $req->intitule;
        $notif->student_id = (int) $req->student_id;
        $notif->annee_scolaire = $req->annee_scolaire;
        $notif->created_at = now();
        $notif->updated_at = now();
        $notif->save();

        return response([
            'message' => 'Requête envoyée !',
            'data' => $notif
        ]);
    }

    public function getDocumentsAsked($ecole_id)
    {
        $documents = DB::table('notifications')
            ->join('students', 'notifications.student_id', '=', 'students.id')
            ->where('notifications.ecole_id', $ecole_id)
            ->where('notifications.processed', 0)
            ->select('notifications.*', 'students.nom as nom_student', 'students.prenom as prenom_student')
            //->orderByDesc('notifications.created_at')
            ->get();

        return response()->json($documents, 200);
    }

    public function validateRequest(Request $req)
    {
        $doc = Notification::find((int) $req->id);
        $doc->processed = 1;
        $doc->updated_at = now();
        $doc->update();
        
        return response([
            'message' => 'Requête traitée avec succès.',
            'data' => $doc
        ]);
    }

    public function getMessages($ecole, $idUser)
    {
        $messages = DB::table('messages')
            //->join('users', 'messages.emetteur', '=', 'users.id')
            ->where('messages.ecole_id', $ecole)
            ->where('messages.read', false)
            //->where('messages.receveur', null)
            //->orWhere('messages.receveur', (int) $idUser)
            //->select('messages.*', 'users.nom as nom_emetteur', 'users.prenom as prenom_emetteur')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($messages, 200);
    }
    
    public function addMessage(Request $req)
    {
        $message = new Message();
        $message->ecole_id = (int) $req->ecole_id;
        $message->emetteur = (int) $req->emetteur;
        $message->receveur = $req->receveur == "NULL" ? null : (int) $req->receveur;
        $message->contenu = $req->contenu;
        $message->read = false;
        $message->created_at = now();
        $message->updated_at = now();
        $message->save();
        
        return response([
            'message' => 'Message envoyé avec succès !',
            'data' => $message
        ]);
    }

    public function updateMessage(Request $req)
    {
        $message = Message::find((int) $req->id);

        if ($message->receveur != null) {
            $message->read = true;
            $message->update();

            return response([
                'message' => 'Update réussi'
            ]);
        }
    }

    public function updateTarif(Request $req)
    {
        $tarif = Tarif::find((int) $req->id);

        if ($tarif != null) {
            $tarif->inscription = $req->inscription;
            $tarif->premiere_tranche = $req->premiere_tranche;
            $tarif->deuxieme_tranche = $req->deuxieme_tranche;
            $tarif->troisieme_tranche = $req->troisieme_tranche;
            $tarif->update();

            return response([
                'message' => 'Tarif modifié'
            ]);
        } else {
            return response([
                'message' => 'Veuillez sélectionner un tarif'
            ]);
        }
    }
    
    public function updateClasse(Request $req)
    {
        $classe = Classe::find((int) $req->id);
        
        if ($classe != null) {
            $classe->nom = $req->nom;
            $classe->update();
            
            return response()->json('Classe modifiée avec succès !', 200);
        }
    }

    public function updateEmploye(Request $req)
    {
        $user = User::find((int) $req->id);

        if ($user) {
           $user->nom = $req->nom;
           $user->prenom = $req->prenom;
           $user->email = $req->email;
           $user->telephone = $req->telephone;
           $user->role_id = $req->role_id;
           $user->update();

           return response()->json('Employé modifié !', 200);
        }
    }
    
    public function updateEvent(Request $req)
    {
        $event = Event::find((int) $req->id);
        
        if ($event != null) {
            $event->title = $req->title;
            $event->description = $req->description;
            $event->start = $req->start;
            $event->end = $req->end;
            $event->update();
            
            return response()->json('Evènement modifié avec succès !', 200);
        } else {
            return response()->json([
                'message' => "L'évènement n'existe pas."
            ], 200);
        }

    }

    public function getEvents($ecole)
    {
        $events = Event::where('ecole_id', (int) $ecole)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($events, 200);
    }

    public function addEvent(Request $req)
    {
        $event = new Event();
        $event->title = $req->title;
        $event->description = $req->description;
        $event->start = $req->start;
        $event->end = $req->end;
        $event->ecole_id = (int) $req->ecole_id;
        $event->annee_scolaire = "2024-2025";
        $event->save();

        return response([
            'message' => 'Evènement enregistré avec succès !',
            'data' => $event
        ]);
    }

    public function addCalendar(Request $req)
    {
        $calendar = new Calendar();
        $calendar->titre = $req->titre;
        $calendar->date = $req->date;
        $calendar->ecole_id = (int) $req->ecole_id;
        $calendar->annee_scolaire = "2024-2025";
        $calendar->created_at = now();
        $calendar->updated_at = now();
        $calendar->save();

        return response([
            'message' => 'Calendrier enregistré avec succès !',
            'data' => $calendar
        ]);
    }

    public function getCalendars($ecole)
    {
        $calendars = Calendar::where('ecole_id', (int) $ecole)
            ->orderByDesc('created_at')
            ->get();
        return response()->json($calendars, 200);
    }

    public function updateCalendar(Request $req)
    {
        $calendar = Calendar::find((int) $req->id);
        $calendar->titre = $req->titre;
        $calendar->date = $req->date;
        $calendar->update();

        return response()->json('Calendrier modifié avec succès !', 200);
    }

    public function deleteCalendar($id)
    {
        $calendar = Calendar::find((int) $id);
        $calendar->delete();
        return response()->json('Calendrier supprimé avec succès !', 200);
    }

    public function deleteLivre($id)
    {
        $livre = Livre::find((int) $id);
        $livre->delete();
        return response()->json('Livre supprimé avec succès !', 200);
    }

    public function addHoraire(Request $req)
    {
        $tranche = new TrancheHoraire();
        $tranche->horaire = $req->horaire;
        $tranche->ecole_id = (int) $req->ecole_id;
        $tranche->created_at = now();
        $tranche->updated_at = now();
        $tranche->save();
        return response([
            'message' => 'Tranche horaire enregistré avec succès !',
            'data' => $tranche
        ]);
    }

    public function getHoraires($ecole)
    {
        $horaires = TrancheHoraire::where('ecole_id', (int) $ecole)->get();
        return response()->json($horaires, 200);
    }

    //Fonction pour cloturer une annee scolaire
    // 1- Mettre a jour la classe de chaque etudiant qui ont valide leur annee
    // 2- Mettre a jour les effectifs de chaque salle de classe
    public function closeYear($ecole)
    {
        $students = Student::where('ecole_id', $ecole)
                        ->where('')                       
                        ->get();
    }

    public function addLivre(Request $req)
    {
        $req->validate([
            'intitule' => 'required',
            'ecole_id' => 'required'
        ]);

        $livre = new Livre();
        $livre->intitule = $req->intitule;
        $livre->ecole_id = (int) $req->ecole_id;
        $livre->created_at = now();
        $livre->updated_at = now();
        $livre->save();

        return response()->json([
            'message' => 'Livre ajouté avec succès !',
            'livre' => $livre
        ], 200);
    }

    public function getLivres($ecole)
    {
        $livres = Livre::where('ecole_id', (int) $ecole)->get();

        return response()->json($livres, 200);
    }

    public function getFeesEcole($ecole)
    {
        $students = Student::where('ecole_id', (int) $ecole)->get();
        $users = User::where('ecole_id', (int) $ecole)->get();
        $events = Event::where('ecole_id', (int) $ecole)->get();
        $paiements = DB::table('paiements')
            ->join('students', 'paiements.student_id', '=', 'students.id')
            ->select('students.nom as nom_student', 'students.prenom as prenom_student', 'paiements.*')
            ->whereDate('paiements.created_at', '=', Carbon::today())
            ->where('paiements.ecole_id', (int) $ecole)
            ->get();

        return response([
            'nb_students' => $students->count(),
            'nb_teachers' => $users->where('role_id', 2)->count(),
            'nb_parents' => $users->where('role_id', 3)->count(),
            'nb_directeurs' => $users->where('role_id', 1)->count(),
            'nb_events' => $events->count(),
            'paiements_today' => $paiements
        ]);
    }

    public function getTokens()
    {
        $tokens = auth()->user()->tokens;

        return response([
            'tokens' => $tokens
        ]);
    }

    public function getSchool($id)
    {
        $directeur = User::where('role_id', 1)->where('ecole_id', (int) $id)->first();

        return response()->json($directeur, 200);
    }
    
    public function linkStudentToParent(Request $req)
    {
        $student = Student::find($req->id);
        $student->parent_id = (int) $req->parent_id;
        $student->update();
        
        return response()->json([
            'message' => "Liaison enregistrée avec succès !"
        ], 200);
    }

    public function addTrimestre(Request $req)
    {
        $trimestre = new Trimestre();
        $trimestre->intitule = $req->intitule;
        $trimestre->ecole_id = (int) $req->ecole_id;
        $trimestre->save();

        return response()->json([
            'message' => "Intitulé enregistré avec succès !"
        ], 200);
    }

    public function getTrimestres($ecole_id)
    {
        $trimestres = Trimestre::where('ecole_id', (int) $ecole_id)->get();
        return response()->json($trimestres, 200);
    }

    public function addSequence(Request $req)
    {
        $sequence = new Sequence();
        $sequence->intitule = $req->intitule;
        $sequence->trimestre_id = (int) $req->trimestre_id;
        $sequence->ecole_id = (int) $req->ecole_id;
        $sequence->save();

        return response()->json([
            'message' => "Intitulé enregistré avec succès !"
        ], 200);
    }

    public function getSequences($ecole_id)
    {
        $sequences = DB::table('sequences')
            ->join('trimestres', 'sequences.trimestre_id', '=', 'trimestres.id')
            ->select('sequences.*', 'trimestres.intitule as intitule_trimestre')
            ->where('sequences.ecole_id', (int) $ecole_id)
            ->get();

        return response()->json($sequences, 200);
    }

    public function generateBulletinClasse($classe_id, $annee, $sequence_id)
    {
        $global_notes = [];  
        $students = Student::where('classe_id', (int) $classe_id)->get();
        $total_students = count($students);

        //Recuperation de toutes les notes de la classe
        $notes = Note::where('annee_scolaire', trim($annee))
        ->where('notes.classe_id', (int) $classe_id)
        ->where('notes.sequence_id', (int) $sequence_id)
        ->get();
        $total_notes = count($notes);

        //Tri sur les notes de la classe
        $array_casted = $notes->all();
        usort($array_casted, function($a, $b) {
            return $b->note <=> $a->note; // Tri décroissant
        });

        foreach ($students as $key => $student) {
            $notes = DB::table('notes')
            ->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
            ->join('groupe_matieres', 'matieres.groupe_matiere_id', '=', 'groupe_matieres.id')
            ->join('coefficient_matieres', 'notes.matiere_id', '=', 'coefficient_matieres.matiere_id')
            ->select('notes.*', 'groupe_matieres.intitule as intitule_groupe', 'matieres.intitule as nom_matiere', 'coefficient_matieres.coefficient as coeff_matiere')
            ->where('notes.annee_scolaire', $annee)
            ->where('notes.sequence_id', (int) $sequence_id)
            ->where('notes.student_id', $student->id)
            ->get();

            array_push($global_notes, [
                "student" => $student,
                "notes" => $notes
            ]);
        }

        $sequence = Sequence::find((int) $sequence_id);
        $classe = Classe::find((int) $classe_id);
        $coefficients = CoefficientMatiere::where('classe_id', (int) $classe_id)->get();
        $ecole = Ecole::find((int) $classe->ecole_id);

        return response()->json([
            'notes' => $global_notes,
            //'notes_triees' => $array_casted,
            'total_notes' => $total_notes,
            'sequence' => $sequence,
            'trimestre' => $sequence->trimestre,
            'annee_scolaire' => $annee,
            'classe' => $classe,
            'class_mater' => $classe->teacher_principal->nom.' '.$classe->teacher_principal->prenom,
            'cycle' => $classe->cycle,
            'coefficients' => $coefficients,
            'total_students_classe' => $total_students,
            'ecole' => $ecole,
        ], 200);
    }

    public function generateBulletinStudent($student_id, $annee, $sequence_id)
    {
        $notes = DB::table('notes')
        ->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
        ->join('groupe_matieres', 'matieres.groupe_matiere_id', '=', 'groupe_matieres.id')
        ->select('notes.*', 'matieres.intitule as intitule_matiere', 'matieres.code as code_matiere', 'groupe_matieres.intitule as intitule_groupe_matiere')
        ->where('notes.annee_scolaire', $annee)
        ->where('notes.student_id', (int) $student_id)
        ->where('notes.sequence_id', (int) $sequence_id)->get();

        $sequence = Sequence::find((int) $sequence_id);

        return response()->json([
            'notes' => $notes,
            'sequence' => $sequence,
            'trimestre' => $sequence->trimestre
        ], 200);
    }
}
