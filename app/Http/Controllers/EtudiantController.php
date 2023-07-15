<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Http\Requests\StoreEtudiantRequest;
use App\Http\Requests\UpdateEtudiantRequest;
use App\Imports\EtudiantImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class EtudiantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $etudiants = Etudiant::query()->paginate();
        foreach($etudiants as $etd){
            $etd->classe = $etd->classe()->first();
        }

        return new Response(json_encode($etudiants));
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
     * @param  \App\Http\Requests\StoreEtudiantRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $etudiant = new Etudiant();
        $classe = Classe::where('code',$request->code_classe)->first();

        $etudiant->matricule = $request->matricule;
        $etudiant->noms = $request->noms;
        $etudiant->sexe = $request->sexe;
        $etudiant->date_naissance = $request->date_naissance;
        $etudiant->classe_id = $classe->id;

        $etudiant->save();

        return redirect()->route('etudiants');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function show(Etudiant $etudiant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function edit(Etudiant $etudiant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEtudiantRequest  $request
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEtudiantRequest $request, Etudiant $etudiant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Etudiant  $etudiant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_etd = Etudiant::findOrFail($id);
        $searched_etd->delete();

        return redirect()->route('etudiants');
    }

    public function view_index()
    {
        $classes = Classe::all();
        $etudiants = Etudiant::query()->paginate();
        foreach($etudiants as $etd){
            $etd->classe = $etd->classe()->first();
        }
        return View::make('pages.importations.etudiants', [
            'classes' => $classes,
            'etudiants' => $etudiants,
        ]);
    }

    public function add_etudiant(Request $request){

        $classe = Classe::where('code',$request->classCode)->first();

        if($request->file("etudiant_file")){
            $import = Excel::import(new EtudiantImport($classe->id),$request->file("etudiant_file"));

            return redirect()->route('etudiants');
        }
    }
}
