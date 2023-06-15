<?php

namespace App\Imports;

use App\Models\UE;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

function testValue($value){
    if ($value == 'non' || $value == 'Non' || $value == 'NON' || $value == 'no' || $value == 'No' || $value == false || $value == 'flase'){
        return false;
    }
    if ($value == 'oui' || $value == 'Oui' || $value == 'OUI' || $value == 'yes' || $value == 'Yes' || $value == true || $value == 'true'){
        return true;
    }
    return false;
}

class UeImport implements ToModel,WithHeadingRow
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



        return new UE([

                'code'=>$row['code']??$row['code_ue'],
                'intitule'=>$row['intitule']??$row['intitule_ue'],
                'semestre'=>$row['semestre']??$row['semestre_ue'],
                'credit'=>$row['credit'],
                'ue_optionelle'=>testValue($row['ue_optionelle']),
                'tp_optionel'=>testValue($row['tp_optionel']),
                'classe_id'=>$this->classId

        ]);
    }
}
