<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\UE;
use App\Models\Note;
use App\Models\CalculMoyenne;
use App\Http\Requests\StoreUERequest;
use App\Http\Requests\UpdateUERequest;
use App\Models\Etudiant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CalculMoyenneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ues = UE::query()->paginate();
        foreach($ues as $ue){
            $ue->classe = $ue->classe()->first();
        }

        return new Response(json_encode($ues));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUERequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $UEs = UE::query()->paginate();
        foreach($UEs as $ue){
            $ue->classe = $ue->classe()->first();
        }

        return new Response(json_encode($UEs));
    }


    /**
     * Show the form for editing the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUERequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUERequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {

    }

    public function view_index()
    {
        $classes = Classe::all();
        $filieres = Filiere::all();
        return View::make('pages.importations.calculmoyenne', ['classes' => $classes, 'filieres' => $filieres]);
    }

    public function calculate(Request $request){
        $classes = Classe::all();
        $filieres = Filiere::all();

        function getPoint($total, $credit){
            if($total < 34.99 ){
                $point = 0;
            }else if($total >= 35 && $total <= 39.99 ){
                $point = 1;
            }else if($total >= 40 && $total <= 44.99 ){
                $point = 1.30;
            }else if($total >= 45 && $total <= 49.99 ){
                $point = 1.70;
            }else if($total >= 50 && $total <= 54.99 ){
                $point = 2;
            }else if($total >= 55 && $total <= 59.99 ){
                $point = 2.30;
            }else if($total >= 60 && $total <= 64.99 ){
                $point = 2.70;
            }else if($total >= 65 && $total <= 69.99 ){
                $point = 3;
            }else if($total >= 70 && $total <= 74.99 ){
                $point = 3.30;
            }else if($total >= 75 && $total <= 79.99 ){
                $point = 3.70;
            }else if($total >= 80 && $total <= 100 ){
                $point = 4;
            }else{
                $point = 4;
            }
            return $point * $credit;
        }

        $classe_select = Classe::where('id',$request->classe)->first();

        $class_Students = $classe_select->etudiants;
        $class_Subjects =  $classe_select->ue;

        $classResult = array();

        foreach($class_Students as $student){
            $courses = $class_Subjects;
            $semesterOneTotal = 0;
            $semesterTwoTotal = 0;
            $nombreEchec = 0;
            $noteManquant = 0;

            foreach ($courses as $course) {
                $note = $course->notes()->where('etudiant_id', $student->id)->get();
                    if($note){
                        if ($course->semestre == 1) {
                            $totalScore = $note[0]["cc"] + $note[0]["sn"] + $note[0]["tp"];
                            $finalpoint = getPoint($totalScore,$course->credit);
                            if($finalpoint < 1){
                                $nombreEchec += 1;
                            }
                            $semesterOneTotal += $finalpoint;
                        } else if ($course->semestre == 2) {
                            $totalScore = $note[0]["cc"] + $note[0]["sn"] + $note[0]["tp"];
                            $finalpoint = getPoint($totalScore,$course->credit);
                            $semesterTwoTotal += $finalpoint;
                        }
                    }
                    else{
                        $noteManquant += 1;
                    }

            }

            $result = new EtudiantResult(
                $student->matricule,
                $semesterOneTotal,
                $semesterTwoTotal,
                $nombreEchec,
                $noteManquant

            );
            array_push($classResult,$result);
        }

//        return $classResult[5]->moyen20;
        return View::make('pages.importations.calculmoyenne',
                [
                'classes' => $classes,
                'filieres' => $filieres,
                'lesNotes'=>$classResult,
                'selected_classe'=>$classe_select
            ]);

    }


}
class EtudiantResult{
    public $matricule;
    public $noms;
    public $sem1Total;
    public $sem2Total;
    public $moyenTotal;
    public $mgp;
    public $moyen20;
    public $mention;

    public $id;
    private static $currentId = 0;

    public $numbreEchec;
    public $noteManquant;

    function __construct($mat, $sem1, $sem2 ,$echec, $manquant) {
        $this->matricule = $mat;
        $this->sem1Total = $sem1;
        $this->sem2Total = $sem2;
        $this->numbreEchec = $echec;
        $this->noteManquant = $manquant;

        $this->id = ++self::$currentId;

        $this->noms = Etudiant::where('matricule',$mat)->first()->noms ?? 'INTROUVABLE';
        $this->moyenTotal = $sem1 + $sem2;
        $this->mgp = ($sem1 + $sem2)/60 ;
        $this->moyen20 = (($sem1 + $sem2)/60) * 5;

        $maMgp = ($sem1 + $sem2)/60;
        if($maMgp > 2 && $echec < 1 && $manquant < 1){
            $this->mention = "Admis";
        }else if($maMgp > 2 && $echec == 1){
            $this->mention = "Autorisé";
        }else if ($maMgp < 2){
            $this->mention = "Reprendre";
        } else{
            $this->mention = "----";
        }
      }
}
