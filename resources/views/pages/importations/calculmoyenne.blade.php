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
                                <form action="" method="POST">
                                    @CSRF
                                    <label for="classe">Classe </label>
                                    <select id="classe" name="classe" class="form-select" required >
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->intitule }} [{{$class->filiere->intitule}}] - {{$class->niveau->intitule}}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                    Ce champ est requis !
                                    </div>
                                    <div class="d-grid gap-2 mt-3">
                                        <button id="import-button" class="btn btn-primary" type="submit">Calculer</button>
                                    </div>
                                </form>

                            </div><br>


                           <!-- <div class="col-sm-8">
                               <label for="classe">Classe</label>
                               <select id="classe" name="classe" class="form-select" required>

                               </select>
                           <div class="invalid-feedback">
                              Ce champ est requis !
                           </div>
                           </div> -->
                           <br>


                            @if(isset($selected_classe))
                                <p>{{ $selected_classe->intitule }} [{{$selected_classe->filiere->intitule}}] - {{$selected_classe->niveau->intitule}}</p>
                            @endif
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
                                            @foreach($selected_classe->etudiants as $etu)
                                            <tr>
                                                <td> dd</td>
                                                <td>{{$etu->matricule}}</td>
                                                <td>{{$etu->noms}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                    </div>






@endsection


@section('customs-scripts')
    <script src="{{ asset('assets/js/share.js') }}"></script>
    </script>
@endsection
