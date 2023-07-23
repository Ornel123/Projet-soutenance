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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
    public function show($id)
    {
        $theNote = Note::find($id);

        if($theNote == null){
            return Redirect::route('notes')->withErrors(["Cette Note N'existe pas !"]);
        }
        if(Auth::user()->role != "Admin" && $theNote->ue->prof_id != Auth::user()->id ){
            return Redirect::route('notes')->withErrors(["Vous N'etes pas autoriser !"]);
        }

        return view('pages.importations.singleNote')
            ->with('note',$theNote);
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

    public function update_note(Request $request,$id)
    {
        $theNote = Note::find($id);


        $theNote->cc =$request->notecc;
        $theNote->tp =$request->notetp;
        $theNote->sn =$request->notesn;

        $theNote->save();
        return Redirect::back()->withSuccess("Note Modifier avec succes !");
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
        $theUe = UE::where('id',Auth::user()->ue_id)->first();
        if(Auth::user()->role == "Admin"){
            $notes = Note::query()->paginate();
        }else if (Auth::user()->role == "Enseignant"){
            $notes = Note::where('ue_id',$theUe->id)->paginate();
        }

        foreach($notes as $note){
            $note->etudiant = $note->etudiant()->first();
            $note->ue = $note->ue()->first();
        }
        $profUe = UE::where('id',Auth::user()->ue_id)->first();
        return View::make('pages.importations.notes', [
            'notes' => $notes,
            'ues' => $ues,
            'theUe' => $profUe
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
        $note = new Note();
        $etudiant = Etudiant::where('matricule',$request->matricule_etudiant)->first();

        if(!$etudiant){
            return Redirect::back()->withErrors(["Cette etudiant N'existe pas !"]);
        }
        $ue = UE::where('code',$request->ueCode)->first();

        $note->etudiant_id =$etudiant->id;
        $note->cc =$request->notecc;
        $note->tp =$request->notetp;
        $note->sn =$request->notesn;
        $note->ue_id =$ue->id;
        $note->annee_scolaire ="2020/2021";

        $note->save();
        return Redirect::back()->withSuccess("Note Enregistrer avec succes !");
    }
}
