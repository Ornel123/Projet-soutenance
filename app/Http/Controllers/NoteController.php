<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Imports\NoteImport;
use App\Models\UE;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notes = Note::query()->paginate();
        foreach($notes as $note){
            $note->etudiant = $note->etudiant()->first();
            $note->ue = $note->ue()->first();
        }

        return new Response(json_encode($notes));
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
     * @param  \App\Http\Requests\StoreNoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'array',
            'notes.*.matricule_etudiant' => 'required|exists:etudiants,matricule',
//            'notes.*.noms_etudiant' => 'required|string|exists:etudiants,noms',
            'notes.*.code_ue' => 'required|exists:u_e_s,code',
            'notes.*.cc' => 'sometimes',
            'notes.*.tp' => 'sometimes',
            'notes.*.sn' => 'sometimes',
        ]);

        if($validator->fails()){
            abort(400, $validator->errors()->toJson());
        }

        foreach($request->notes as $note){
            Note::create([
                'ue_id' => UE::query()->where('code', $note['code_ue'])->first()->id,
                'etudiant_id' => Etudiant::query()->where('matricule', $note['matricule_etudiant'])->first()->id,
                'cc' => $note['cc'] ?? null,
                'tp' => $note['tp'] ?? null,
                'sn' => $note['sn'] ?? null,
                'annee_scolaire' => '2022-2023'
            ]);
        }

        return Response(json_encode('Les notes ont été enregistrées avec succès !', 201));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNoteRequest  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_note = Note::findOrFail($id);
        $searched_note->delete();

        return redirect()->route('notes');
    }

    public function view_index()
    {
        $ues = UE::all();
        $notes = Note::query()->paginate();
        foreach($notes as $note){
            $note->etudiant = $note->etudiant()->first();
            $note->ue = $note->ue()->first();
        }
        return View::make('pages.importations.notes', [
            'notes' => $notes,
            'ues' => $ues
        ]);
    }

    public function add_notes(Request $request){
        $ue = UE::where('code',$request->ueCode)->first();
        $anneScholaire = "2020/2021";

        if($request->file("notes")){
            $import = Excel::import(new NoteImport($ue->id,$anneScholaire),$request->file("notes"));

            return redirect()->route('notes');
        }
    }
    public function add_notes_form(Request $request){
        return redirect()->route('notes');
    }
}
