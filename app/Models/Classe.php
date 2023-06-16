<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
    protected $fillable = [
        "code",
        "intitule",
        "filiere_id",
        "niveau_id",
    ];

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
    public function ue()
    {
        return $this->hasMany(UE::class);
    }


}
