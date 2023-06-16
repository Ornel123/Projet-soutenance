<?php

namespace App\Imports;

use App\Models\Etudiant;
use App\Models\Note;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NoteImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $ue_id;
    private $anneScholaire;

    public function __construct($ue,$ann)
    {
        $this->ue_id = $ue;
        $this->anneScholaire = $ann;
    }


    public function model(array $row)
    {
        $existingCcpp = Etudiant::where('matricule',$row['matricule'])->first();


        $existingCc = Note::where('etudiant_id', $existingCcpp->id)->whereNotNull('cc')->where('ue_id',$this->ue_id)->get();
        $existingTp = Note::where('etudiant_id', $existingCcpp->id)->whereNotNull('tp')->where('ue_id',$this->ue_id)->get();
        $existingSn = Note::where('etudiant_id', $existingCcpp->id)->whereNotNull('sn')->where('ue_id',$this->ue_id)->get();


        if($existingCc->isNotEmpty()){
            $existingCc->first()->update([
                'tp'=>$row['tp'],
                'sn'=>$row['sn']
            ]);
        }
        else if($existingTp->isNotEmpty()){
            $existingTp->first()->update([
                'cc'=>$row['cc'],
                'sn'=>$row['sn']
            ]);
        }
        else if($existingSn->isNotEmpty()){
            $existingSn->first()->update([
                'cc'=>$row['cc'],
                'tp'=>$row['tp'],
            ]);
        }

        else{
            return new Note([
                'cc'=>$row['cc'],
                'tp'=>$row['tp'],
                'sn'=>$row['sn'],
                'ue_id'=>$this->ue_id,
                'etudiant_id'=>$existingCcpp->id,
                'annee_scolaire'=>$this->anneScholaire
            ]);
        }
    }
}
