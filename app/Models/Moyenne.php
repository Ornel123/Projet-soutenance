<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moyenne extends Model
{
    use HasFactory;
    protected $fillable = [
        "points",
        "note",
        "credit",
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
