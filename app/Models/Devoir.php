<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Livre;

class Devoir extends Model
{
    use HasFactory;
    
    public function livre(): HasOne
    {
        return $this->hasOne(Livre::class);
    }
}
