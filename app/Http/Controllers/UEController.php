<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\UE;
use App\Http\Requests\StoreUERequest;
use App\Http\Requests\UpdateUERequest;
use App\Imports\UeImport;
use App\Models\User;
// use http\Env\Request;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class UEController extends Controller
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
        $validator = Validator::make($request->all(), [
            'ues' => 'array',
            'ues.*.code' => 'required|string|between:2,15|unique:u_e_s,code',
            'ues.*.code_classe' => 'required|string|between:2,15|exists:classes,code',
            'ues.*.intitule' => 'required|string|between:3,60',
            'ues.*.semestre' => 'required|integer',
            'ues.*.credit' => 'required|integer',
            'ues.*.ue_optionelle' => 'required|boolean',
            'ues.*.tp_optionel' => 'required|boolean',
        ]);

        if($validator->fails()){
            abort(400, $validator->errors()->toJson());
        }

        foreach($request->ues as $ue){
            UE::create([
                'code' => $ue['code'],
                'intitule' => $ue['intitule'],
                'classe_id' => Classe::query()->where('code', $ue['code_classe'])->first()->id,
                'semestre' => $ue['semestre'],
                'credit' => $ue['credit'],
                'ue_optionelle' => $ue['ue_optionelle'],
                'tp_optionel' => $ue['tp_optionel']
            ]);
        }

        return Response(json_encode('Les UEs ont été enregistrées avec succès !', 201));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UE  $uE
     * @return \Illuminate\Http\Response
     */
    public function show(UE $uE)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UE  $uE
     * @return \Illuminate\Http\Response
     */
    public function edit(UE $uE)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUERequest  $request
     * @param  \App\Models\UE  $uE
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUERequest $request, UE $uE)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UE  $uE
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_ue = UE::findOrFail($id);
        if(count($searched_ue->notes) > 0){
            return Redirect::back()->withErrors(["Cette UE posses les notes !"]);
        }

        //Suprimmer le proff lie a cette UE
        $theProf = User::where('id',$searched_ue->prof_id)->first();
        if($theProf){
            $theProf->delete();
        }

        $searched_ue->delete();
        return redirect()->route('ues');
    }

    public function view_index()
    {
        $classes = Classe::all();
        $ues = UE::query()->paginate();
        foreach($ues as $ue){
            $ue->classe = $ue->classe()->first();
        }
        return View::make('pages.importations.ues', [
            'classes' => $classes,
            'ues' => $ues,
        ]);
    }

    public function add_ue(Request $request){

        $classe = Classe::where('code',$request->classCode)->first();
        if($request->file("ue_file")){
            $import = Excel::import(new UeImport($classe->id), $request->file("ue_file"));

            return redirect()->route('ues');
        }

    }

    public function add_ueForm(Request $request){

        $ue = new UE();
        $classe = Classe::where('code',$request->code_classe)->first();

        $ue->classe_id = $classe->id;
        $ue->code = $request->code;
        $ue->intitule = $request->intitule;
        $ue->semestre = $request->semestre;
        $ue->credit = $request->credit;
        $ue->ue_optionelle = testValue($request->ue_optionelle);
        $ue->tp_optionel = testValue($request->tp_optionele);
        $ue->save();

        return redirect()->route('ues');


    }

}
function testValue($value){
    if ($value == 'non' || $value == 'Non' || $value == 'NON' || $value == 'no' || $value == 'No' || $value == false || $value == 'flase'){
        return false;
    }
    if ($value == 'oui' || $value == 'Oui' || $value == 'OUI' || $value == 'yes' || $value == 'Yes' || $value == true || $value == 'true'){
        return true;
    }
    return false;
}
