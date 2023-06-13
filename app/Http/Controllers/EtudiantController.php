<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etudiant;
use App\Http\Requests\StoreEtudiantRequest;
use App\Http\Requests\UpdateEtudiantRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

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
        $validator = Validator::make($request->all(), [
            'etudiants' => 'array',
            'etudiants.*.matricule' => 'required|string|between:7,7|unique:etudiants,matricule',
            'etudiants.*.noms' => 'required|string|between:3,60',
            'etudiants.*.sexe' => 'required|string',
            'etudiants.*.date_naissance' => 'required|string',
            'etudiants.*.code_classe' => 'required|string|between:2,15|exists:classes,code',
        ]);

        if($validator->fails()){
            abort(400, $validator->errors()->toJson());
        }

        foreach($request->etudiants as $etd){
            Etudiant::create([
                'matricule' => $etd['matricule'],
                'noms' => $etd['noms'],
                'sexe' => $etd['sexe'],
                'date_naissance' => $etd['date_naissance'],
                'classe_id' => Classe::query()->where('code', $etd['code_classe'])->first()->id,
            ]);
        }

        return Response(json_encode('Les étudiants ont été enregistrés avec succès !', 201));
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

        return Response(json_encode([
            'message' => 'L\'étudiant a été supprimé avec succès !'
        ]));
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
}
