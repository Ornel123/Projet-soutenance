@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Modifier la note</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">admin</li>
                <li class="breadcrumb-item active">note</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{Session::get('success')}}
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="card">
        <div class="card-header">
            <p>Note de <b>{{$note->etudiant->noms}} </b></p>
            <p>UE: <b>{{$note->ue->intitule}} - ({{$note->ue->code}})</b></p>
        </div>
        <div class="card-body">
            <div>
            <form method="post" class="row d-flex justify-content-center" id="classe-form">
                                        @CSRF
                                        @method('PUT')
                                        <div class="col-md-7">
                                        <div class="row mb-3">
                                                <label for="code">Note CC:</label>
                                                <div class="col-sm-10">
                                                    <input value="{{$note->cc}}" min="0" max="20" required name="notecc" type="number" step="0.01" class="form-control" placeholder="Email du compte">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code">Note TP:</label>
                                                <div class="col-sm-10">
                                                    <input value="{{$note->tp}}" min="0" max="40" required name="notetp" type="number" step="0.01" class="form-control" placeholder="Nom du compte">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code">Note SN:</label>
                                                <div class="col-sm-10">
                                                    <input required value="{{$note->sn}}" min="0" max="40" step="0.01" id="intitule" name="notesn" type="number" class="form-control" placeholder="Mot de passe">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-outline-primary">Enregistrer</button>
                                            </div>
                                        </div>
                                    </form>
            </div>
        </div>
        <div class="card-footer">Modifier la note</div>
    </div>


@endsection

