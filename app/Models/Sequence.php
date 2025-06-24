<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use HasFactory;

    public function trimestre()
    {
        return $this->belongsTo(Trimestre::class);
    }
}
