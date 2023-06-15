<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Http\Requests\StoreNiveauRequest;
use App\Http\Requests\UpdateNiveauRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NiveauImport;

class NiveauController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $niveaux = Niveau::query()->paginante();
        foreach($niveaux as $niv){
            $niv->nombre_classes = $niv->classes()->count();
        }
        return new Response(json_encode($niveaux), 200);
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
     * @param  \App\Http\Requests\StoreNiveauRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNiveauRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'niveaux' => 'array',
            'niveaux.*.code' => 'required|string|between:2,15|unique:niveaux,code',
            'niveaux.*.intitule' => 'required|string|between:3,60',
        ]);

        if($validator->fails()){
            abort(400, $validator->errors()->toJson());
        }

        foreach($request->niveaux as $niveau){
            Niveau::create([
                'code' => $niveau['code'],
                'intitule' => $niveau['intitule']
            ]);
        }

        return Response(json_encode('Les niveaux ont été enregistrés avec succès !', 201));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Niveau  $niveau
     * @return \Illuminate\Http\Response
     */
    public function show(Niveau $niveau)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Niveau  $niveau
     * @return \Illuminate\Http\Response
     */
    public function edit(Niveau $niveau)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNiveauRequest  $request
     * @param  \App\Models\Niveau  $niveau
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNiveauRequest $request, Niveau $niveau)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Niveau  $niveau
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_level = Niveau::findOrFail($id);
        $searched_level->delete();

        return Response(json_encode([
            'message' => 'Le niveau a été supprimé avec succès !'
        ]));
    }

    public function view_index()
    {
        $niveaux = Niveau::query()->paginate();
        // foreach($niveaux as $niveau){
        //     $niveau->nombre_classes = $niveau->classes()->count();
        // }
        return View::make('pages.importations.niveaux', ['niveaux' => $niveaux]);
    }

    public function add_niveau(Request $request)
    {
        if($request->file("niveau")){
            $import = Excel::import(new NiveauImport, $request->file("niveau"));
            $msg_danger = "Data Uploaded failed! ";
            if ($import) {
                return redirect()->route('niveaux');
            }else{
               return $msg_danger;
            }
        }else{
            return dump($request->allFiles());
        }
    }
}
