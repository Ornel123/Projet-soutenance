<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\UE;
use App\Http\Requests\StoreUERequest;
use App\Http\Requests\UpdateUERequest;
use http\Env\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

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
        $searched_ue->delete();

        return Response(json_encode([
            'message' => 'L\'UE a été supprimée avec succès !'
        ]));
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
}
