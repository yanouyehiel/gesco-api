<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absence;
use App\Models\Classe;

class Student extends Model
{
    use HasFactory;

    public function absences()
    {
        return $this->hasOneThrough(Absence::class, Classe::class);
    }
}
