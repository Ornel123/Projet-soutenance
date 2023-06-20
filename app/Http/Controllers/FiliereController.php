<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Http\Requests\StoreFiliereRequest;
use App\Http\Requests\UpdateFiliereRequest;
use App\Imports\FiliereImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class FiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filieres = Filiere::query()->paginate();
        foreach($filieres as $filiere){
            $filiere->nombre_classes = $filiere->classes()->count();
        }
        return new Response(json_encode($filieres), 200);
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
     * @param  \App\Http\Requests\StoreFiliereRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filieres' => 'array',
            'filieres.*.code' => 'required|string|between:2,15|unique:filieres,code',
            'filieres.*.intitule' => 'required|string|between:3,60',
        ]);

        if($validator->fails()){
            abort(400, $validator->errors()->toJson());
        }

        foreach($request->filieres as $filiere){
            Filiere::create([
                'code' => $filiere['code'],
                'intitule' => $filiere['intitule']
            ]);
        }

        return Response(json_encode('Les filières ont été enregistrées avec succès !', 201));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Filiere  $filiere
     * @return \Illuminate\Http\Response
     */
    public function show(Filiere $filiere)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Filiere  $filiere
     * @return \Illuminate\Http\Response
     */
    public function edit(Filiere $filiere)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFiliereRequest  $request
     * @param  \App\Models\Filiere  $filiere
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFiliereRequest $request, Filiere $filiere)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Filiere  $filiere
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_sector = Filiere::findOrFail($id);
        $searched_sector->delete();

        return Response(json_encode([
            'message' => 'La filière a été supprimée avec succès !'
        ]));
    }

    //////////////////////////
    /// VIEWS
    /// /////////////////////

    public function view_index()
    {
        $filieres = Filiere::query()->paginate();
        // foreach($filieres as $filiere){
        //     $filiere->nombre_classes = $filiere->classes()->count();
        // }
        return View::make('pages.importations.filieres', ['filieres' => $filieres]);
    }
    public function add_filiere(Request $request)
    {
        $filieres = Filiere::query()->paginate();
        if($request->file("filiere")){
            $import = Excel::import(new FiliereImport, $request->file("filiere"));

            $msg_danger = "Data Uploaded failed! ";
            if ($import) {
                return View::make('pages.importations.filieres', ['filieres' => $filieres]);
            }else{
               return $msg_danger;
            }
        }else{
            return dump($request->allFiles());
        }
    }
}
