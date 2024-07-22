<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = ['nom', 'prenom', 'email', 'password', 'telephone'];

    public function getName()
    {
        return $this->belongsTo(Ecole::class);
    }

    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
}
