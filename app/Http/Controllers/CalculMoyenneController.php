<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Filiere;
use App\Models\UE;
use App\Models\Note;
use App\Models\CalculMoyenne;
use App\Http\Requests\StoreUERequest;
use App\Http\Requests\UpdateUERequest;
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

        $classe_select = Classe::where('id',$request->classe)->first();
        $classes = Classe::all();
        $filieres = Filiere::all();

        $notes = Note::whereIn('u_e_id', $classe_select->ue->pluck('id')->toArray())->get();

        return View::make('pages.importations.calculmoyenne',
            [
                'classes' => $classes,
                'filieres' => $filieres,
                'selected_classe'=>$classe_select,
                'notes'=>$notes
            ]);

    }
}
