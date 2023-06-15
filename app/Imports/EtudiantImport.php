<?php

namespace App\Imports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EtudiantImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $classId;

    public function __construct($clas)
    {
        $this->classId = $clas;
    }

    public function model(array $row)
    {
        return new Etudiant([
            'matricule'=>$row['matricule'],
            'noms'=>$row['noms'],
            'sexe'=>$row['sexe'],
            'date_naissance'=>$row['date_naissance'],
            'classe_id'=>$this->classId
        ]);
    }
}
