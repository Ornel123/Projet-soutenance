<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use App\Imports\ClasseImport;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Classe::query()->paginate();
        foreach($classes as $classe){
            $classe->filiere = $classe->filiere()->first();
            $classe->niveau = $classe->niveau()->first();
        }

        return new Response(json_encode($classes));
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
     * @param  \App\Http\Requests\StoreClasseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClasseRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'classes' => 'array',
            'classes.*.code' => 'required|string|between:2,15|unique:classes,code',
            'classes.*.code_filiere' => 'required|string|between:2,15|exists:filieres,code',
            'classes.*.code_niveau' => 'required|string|between:2,15|exists:niveaux,code',
            'classes.*.intitule' => 'required|string|between:3,60',
        ]);

        if($validator->fails()){
            abort(400, $validator->errors()->toJson());
        }

        foreach($request->classes as $classe){
            Classe::create([
                'code' => $classe['code'],
                'intitule' => $classe['intitule'],
                'filiere_id' => Filiere::query()->where('code', $classe['code_filiere'])->first()->id,
                'niveau_id' => Niveau::query()->where('code', $classe['code_niveau'])->first()->id
            ]);
        }

        return Response(json_encode('Les classes ont été enregistrées avec succès !', 201));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function show(Classe $classe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function edit(Classe $classe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClasseRequest  $request
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClasseRequest $request, Classe $classe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Classe  $classe
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_class = Classe::findOrFail($id);

        if(count($searched_class->ue) > 0 || count($searched_class->etudiants) >0 ){
            return Redirect::back()->withErrors(["Cette classe est lie a des UE et des Etudiants !"]);
        }

        $searched_class->delete();

        return redirect()->route('classes.all');
    }

    public function view_index()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::all();
        $classes = Classe::query()->paginate();
        foreach($classes as $classe){
            $classe->filiere = $classe->filiere()->first();
            $classe->niveau = $classe->niveau()->first();
        }
        return View::make('pages.importations.classes', [
            'filieres' => $filieres,
            'niveaux' => $niveaux,
            'classes' => $classes
        ]);
    }
    public function add_classe(Request $request)
    {
        $filiere = Filiere::where('code',$request->filiere)->first();
        $niveau = Niveau::where('code',$request->niveau)->first();
        if($request->file("classe")){
            $import = Excel::import(new ClasseImport($filiere->id,$niveau->id), $request->file("classe"));
            $msg_danger = "Data Uploaded failed! ";
            if ($import) {
                    $filieres = Filiere::all();
                    $niveaux = Niveau::all();
                    $classes = Classe::query()->paginate();
                    foreach($classes as $classe){
                        $classe->filiere = $classe->filiere()->first();
                        $classe->niveau = $classe->niveau()->first();
                    }
                    return View::make('pages.importations.classes', [
                        'filieres' => $filieres,
                        'niveaux' => $niveaux,
                        'classes' => $classes
                    ]);
            }else{
               return $msg_danger;
            }
        }else{
            return dump($request->allFiles());
        }
    }

    public function add_classe_form(Request $request){
            $filiere = Filiere::where('code',$request->code_filiere)->first();
            $niveau = Niveau::where('code',$request->code_niveau)->first();
            $code = $request->code;
            $intitule = $request->intitule;

            $classe = new Classe();

            $classe->code = $code;
            $classe->intitule = $intitule;
            $classe->filiere_id = $filiere->id;
            $classe->niveau_id = $niveau->id;

            if($classe->save()){
                return redirect()->route('classes.all');

            }

    }
}
