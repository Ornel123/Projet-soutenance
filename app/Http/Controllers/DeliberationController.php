<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\UE;
use App\Models\Note;
use App\Models\CalculMoyenne;
use App\Models\DeliberationMoyenne;
use App\Http\Requests\StoreUERequest;
use App\Http\Requests\UpdateUERequest;
use http\Env\Request;
use Illuminate\Http\Response;
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
        $NOTES = Note::query()->get();
        $notes = [];
        foreach($NOTES as $note){
            $note->etudiant = $note->etudiant()->first();
            $note->ue = $note->ue()->first();
            // if($note["ue"]["code"] == $request->filliere_id){
            //   array_push($notes, $note);  
            // }
            array_push($notes, $note); 
        }
//// Remplir un tableaux avec les informations de chaque élève

        $students = [];
        foreach($notes as $note){
            $found = false;
            $studentsCount = count($students);
            $i = 0;
            while( $i < $studentsCount ){
                if($students[$i]["noms"] == $note["etudiant"]["noms"]){
                    $found = true;
                        if($note["ue"]["semestre"] == "1"){
                            if($note["sn"] != null){
                                $students[$i]["sn1"] = $note["sn"];
                            }
                             if($note["cc"] != null){
                                $students[$i]["cc1"] = $note["cc"];
                            }
                             if($note["tp"] != null){
                                $students[$i]["tp1"] = $note["tp"];
                            }
                        }else{
                            if($note["sn"] != null){
                                $students[$i]["sn2"] = $note["sn"];
                            }
                             if($note["cc"] != null){
                                $students[$i]["cc2"] = $note["cc"];
                            }
                             if($note["tp"] != null){
                                $students[$i]["tp2"] = $note["tp"];
                            }                       
                        }
                }
                $i++;
            }
            
            if($found == false AND $note["ue"]["classe_id"] == $request->classe_id){
               
                if($note["ue"]["semestre"] == "1"){
                    array_push($students, ["noms" => $note["etudiant"]["noms"], "matricule" => $note["etudiant"]["matricule"], "code" => $note["ue"]["code"], "cc1" => $note["cc"], "tp1" => $note["tp"], "sn1" => $note["sn"], "cc2" => 0, "tp2" => 0, "sn2" => 0, "moyenne1" => 0, "moyenne2" => 0, "mention" => ""]);
                }else if($note["ue"]["semestre"] == "2"){
                    array_push($students, ["noms" => $note["etudiant"]["noms"], "matricule" => $note["etudiant"]["matricule"], "code" => $note["ue"]["code"], "cc1" => 0, "tp1" => 0, "sn1" => 0, "cc2" => $note["cc"], "tp2" => $note["tp"], "sn2" => $note["sn"], "moyenne1" => 0, "moyenne2" => 0, "mention" => ""]);
                }
            }
        }

// Remplir le tableau d'étudiant avec leurs moyennes
            $studentsCount = count($students);
             $i = 0;
            while( $i < $studentsCount ){
               $total1 = 0;
               if($students[$i]["sn1"] != null){
                $total1 += $students[$i]["sn1"];
            }
             if($students[$i]["cc1"] != null){
                $total1 += $students[$i]["cc1"];
            }
             if($students[$i]["tp1"] != null){
                $total1 += $students[$i]["tp1"];
            }              
               $students[$i]["moyenne1"] = $total1/5;

               $total2 = 0;
               if($students[$i]["sn2"] != null){
                $total2 += $students[$i]["sn2"];
            }
             if($students[$i]["cc2"] != null){
                $total2 += $students[$i]["cc2"];
            }
             if($students[$i]["tp2"] != null){
                $total2 += $students[$i]["tp2"];
            }              
               $students[$i]["moyenne2"] = $total2/5;

               
               if(($students[$i]["sn1"] == null OR $students[$i]["cc1"] == null OR $students[$i]["tp1"] == null OR $students[$i]["sn2"] == null OR $students[$i]["cc2"] == null OR $students[$i]["tp2"] == null) OR ($total1+$total2) < 70){
                $students[$i]["mention"] = "éliminé";
               }else{
                $students[$i]["mention"] = "retenu";
               }
               $i++;
            }

        return new Response(json_encode($students));
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
}
