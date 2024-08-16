<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoefficientMatiere;

class Matiere extends Model
{
    use HasFactory;

    /**
     * Get all of the coefficents for the Matiere
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coefficients()
    {
        return $this->hasMany(CoefficientMatiere::class);
    }
}
