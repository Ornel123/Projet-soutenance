<?php

namespace App\Imports;

use App\Models\Classe;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClasseImport implements ToModel,WithHeadingRow
{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $filiere;
    private $niveau;

    public function __construct($fil,$niv)
    {
        $this->filiere = $fil;
        $this->niveau = $niv;
    }

    public function model(array $row)
    {
        return new Classe([
            'code'=>$row["code"]??$row["CODE"]??$row["code_classe"],
            'intitule'=>$row["intitule"]??$row["INTITULE"]??$row["intitule_classe"],
            'filiere_id'=>$this->filiere,
            'niveau_id'=>$this->niveau
        ]);
    }
}
