@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

<div class="pagetitle">
        
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">Importations</li>
                <li class="breadcrumb-item active">CalculMoyenne</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
 




                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="position: relative;">
                                <button onclick="showSummaryContainer()" style="position: absolute; right: 0;" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-arrow-left-square"></i>
                                </button>
                                Calcul des Moyennes
                            </h5>

                            <!-- Bordered Tabs Justified -->

                            <div class="col-sm-8">
                                <label for="classe">Filliere</label>
                                <select id="filiere" name="filiere" class="form-select" required>
                                        
                                    @foreach ($filieres as $filiere)
                                         <option value="{{ $filiere->id }}>">{{ $filiere->code }}</option>
                                    @endforeach
                                </select>
                            <div class="invalid-feedback">
                               Ce champ est requis !
                            </div>
                            </div><br>
                           
                           <div class="col-sm-8">
                               <label for="classe">Classe</label>
                               <select id="classe" name="classe" class="form-select" required></select>
                           <div class="invalid-feedback">
                              Ce champ est requis !
                           </div>
                           </div><br> 

                            <div class="d-grid gap-2 mt-3">
                                    <button id="import-button" onclick="CalculMoyenne()" class="btn btn-primary" type="button">Calculer</button>
                                    <button id="import-loader" class="btn btn-primary visually-hidden" type="button" disabled="">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Veuillez patienter...
                                    </button><br>
                                </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                        <th scope="col">#</th>
                                            <th scope="col">Matricule</th>
                                            <th scope="col">Noms & prenoms</th>
                                            <th scope="col">Semestre 1</th>
                                            <th scope="col">Semestre 2</th>
                                            <th scope="col">Moyenne totale</th>
                                            <th scope="col">MGP</th>
                                            <th scope="col">Moyenne /20</th>
                                            <th scope="col">Mention</th>
                                         
                                        </tr>
                                        </thead>
                                        <tbody id="calculmoyenne-result">

                                        </tbody>
                                    </table>
                                </div> 
                               
                    </div>

            




@endsection


@section('customs-scripts')
    <script src="{{ asset('assets/js/share.js') }}"></script>
    <script src="{{ asset('assets/js/importations/CalculeMoyenne.js') }}"></script>
    <script>
        allClasses = {!! json_encode($classes) !!};
    </script>
@endsection
