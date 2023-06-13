<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = [
        "cc",
        "tp",
        "sn",
        "annee_scolaire",
        "etudiant_id",
        "ue_id",
    ];

    public function etudiant()
    {
       return $this->belongsTo(Etudiant::class);
    }

    public function ue()
    {
        return $this->belongsTo(UE::class);
    }
}
