<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeClasse;
use App\Models\Ecole;
use App\Models\Student;
use App\Models\Cours;
use App\Models\Note;
use App\Models\Cycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classe extends Model
{
    use HasFactory;

    public function typeClasse()
    {
        return $this->hasOne(TypeClasse::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
    
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    public function teacher_principal()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }
}
