<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absence;
use App\Models\Classe;
use App\Models\Note;
use App\Models\Devoir;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['matricule', 'nom', 'prenom', 'classe_id', 'date_scolarisation', 'date_naissance', 'lieu_naissance', 'sexe', 'ecole_id'];

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function classe()
    {
        return $this->hasOne(Classe::class, 'id', 'classe_id');
    }
    
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function devoirs()
    {
        return $this->hasMany(Devoir::class, 'classe_id');
    }
}
