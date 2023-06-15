<?php

namespace App\Imports;

use App\Models\Filiere;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FiliereImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Filiere([
            'code'=>$row["code"]??$row["CODE"]??$row["code_filiere"],
            'intitule'=>$row["intitule"]??$row["INTITULE"]??$row["intitule_filiere"]
            // 'classe'=>$row["classe"]??$row["classe_filiere"]??$row["CLASSE"],
            // 'niveau'=>$row["niveau"]??$row["NIVEAU"]??$row["niveau_filiere"]
        ]);
    }
}
