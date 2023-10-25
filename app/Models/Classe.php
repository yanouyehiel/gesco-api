<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TypeClasse;
use App\Models\Ecole;

class Classe extends Model
{
    use HasFactory;

    public function typeClasse()
    {
        return $this-hasOne(TypeClasse::class);
    }
}
