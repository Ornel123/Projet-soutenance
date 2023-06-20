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
                                <div >
                                <form action="" method="POST" class="row">
                                    @CSRF
                                    <div class="col-sm-6 float-left">
                                        <label for="classe">Classe </label>
                                        <select id="classe" name="classe" class="form-select" required >
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->intitule }} [{{$class->filiere->intitule}}] - {{$class->niveau->intitule}}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                        Ce champ est requis !
                                        </div>

                                    </div>

                                    <div class="col-sm-6 float-right">
                                        <h5>Critere de deliberation (Optionelle)</h5>
                                        <div>
                                            <label for="mgpMin">MGP Minimum</label>
                                            <input type="number" id="mgpMin" name="mgpMin" class="form-control" min="0" max="4" step="0.1" value="{{$mgpMinimum ?? 1.5}}">

                                            <!-- <label for="mgpMax">MGP Maximum</label>
                                            <input type="number" id="mgpMax" name="mgpMax" class="form-control" min="0" max="4"> -->

                                            <label for="echecNum">Nombre Dechec maximum</label>
                                            <input type="number" id="echecNum" name="echecNum" class="form-control" min="0" max="4" value="{{$echecMax ?? 2}}">
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 mt-3 col-sm-12">
                                            <button id="import-button" class="btn btn-primary" type="submit">Calculer</button>
                                        </div>
                                </form>
                                </div>
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
                                            <th scope="col">Moyenne/20</th>
                                            <th scope="col">Mention</th>
                                            <th scope="col">E</th>
                                            <th scope="col">M</th>

                                        </tr>
                                        </thead>
                                        <tbody id="calculmoyenne-result">
                                            @if(isset($lesNotes))
                                                @foreach($lesNotes as $note)
                                                    @if($note->mention != 'deliberer')
                                                    <tr>
                                                        <td>{{$note->id}}</td>
                                                        <td>{{$note->matricule}}</td>
                                                        <td>{{$note->noms}}</td>
                                                        <td>{{$note->sem1Total}}</td>
                                                        <td>{{$note->sem2Total}}</td>
                                                        <td>{{$note->moyenTotal}}</td>
                                                        <td>{{number_format($note->mgp, 1)}}</td>
                                                        <td>{{number_format($note->moyen20, 1)}}</td>
                                                        <td>{{$note->mention}}</td>
                                                        <td>{{$note->numbreEchec}}</td>
                                                        <td>{{$note->noteManquant}}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                    </div>

                    @if(isset($selected_classe))
                                <p>Etudiants Deliberer Dans: {{ $selected_classe->intitule }} [{{$selected_classe->filiere->intitule}}] - {{$selected_classe->niveau->intitule}}</p>
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
                                            <th scope="col">Moyenne/20</th>
                                            <th scope="col">Mention</th>
                                            <th scope="col">E</th>
                                            <th scope="col">M</th>

                                        </tr>
                                        </thead>
                                        <tbody id="calculmoyenne-result">
                                            @if(isset($lesNotes))
                                                @foreach($lesNotes as $note)
                                                    @if($note->mention == 'deliberer')
                                                    <tr>
                                                        <td>{{$note->id}}</td>
                                                        <td>{{$note->matricule}}</td>
                                                        <td>{{$note->noms}}</td>
                                                        <td>{{$note->sem1Total}}</td>
                                                        <td>{{$note->sem2Total}}</td>
                                                        <td>{{$note->moyenTotal}}</td>
                                                        <td>{{number_format($note->mgp, 1)}}</td>
                                                        <td>{{number_format($note->moyen20, 1)}}</td>
                                                        <td>{{$note->mention}}</td>
                                                        <td>{{$note->numbreEchec}}</td>
                                                        <td>{{$note->noteManquant}}</td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                    </div>






@endsection


@section('customs-scripts')
    <script src="{{ asset('assets/js/share.js') }}"></script>
    </script>
@endsection
