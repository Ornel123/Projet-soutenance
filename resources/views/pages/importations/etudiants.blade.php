@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Etudiants</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">Importations</li>
                <li class="breadcrumb-item active">Etudiants</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div id="import-container">


                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Unités d'Enseignement</h5>
                     <div class="col-md-12">

                            <div class="row">
                <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Matricule</th>
                                            <th scope="col">Noms & Prénoms</th>
                                            <th scope="col">Sexe</th>
                                            <th scope="col">Date de naissance</th>
                                            <th scope="col">Classe</th>
                                            <th scope="col">Retirer</th>
                                        </tr>
                                        </thead>
                                        <tbody id="ues-result">
                                            @foreach($etudiants as $etud)
                                            <tr>
                                                <td>{{$etud->id}}</td>
                                                <td>{{$etud->matricule}}</td>
                                                <td>{{$etud->noms}}</td>
                                                <td>{{$etud->sexe}}</td>
                                                <td>{{$etud->date_naissance}}</td>
                                                <td>{{$etud->classe->intitule}}</td>
                                                <td>
                                                <button class="btn btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                        </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title" style="position: relative;">
                                <button onclick="showSummaryContainer()" style="position: absolute; right: 0;" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-arrow-left-square"></i>
                                </button>
                                Importation
                            </h5>

                            <!-- Bordered Tabs Justified -->
                            <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100 active" id="import-by-file-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Ajouter par fichier Excel</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                    <button class="nav-link w-100" id="import-by-form-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Ajouter par formulaire</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2" id="borderedTabJustifiedContent">
                            <div class="tab-pane fade mt-3 show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="import-by-file-tab">
                                    <div class="row d-flex flex-column justify-content-center align-items-center">
                                        <div class="drag-area" id="drag-area">
                                            <form
                                            enctype="multipart/form-data"
                                            method="POST"
                                            style="display:flex;
                                            justify-content:space-around;
                                            flex-direction:column;
                                            gap:10px;">
                                                @CSRF
                                                <select id="code_niveau" name="classCode" class="form-select" required>
                                                        <option selected disabled>Choissisez la classe</option>
                                                        @foreach($classes as $clas)
                                                            <option value="{{$clas->code}}">{{$clas->intitule}}</option>
                                                        @endforeach
                                                    </select>
                                                <input class="form-control" type="file" id="formFile" name="etudiant_file" required accept=".xlsx,.csv" />
                                                <button class="btn btn-success browse-button" id="browse-button">Ajouter</button>
                                            </form>
                                        </div>
                                        <div class="text-md-center mt-3" id="imported-file-name" style="display: none;">
                                            <h6 id="file-name"></h6>
                                        </div>
                                        <div class="progress-cont mt-1" id="progress-component" style="display: none;">
                                            <div class="progress bg-info" id="progress" style="width: 80px"> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-3" id="bordered-justified-profile" role="tabpanel" aria-labelledby="import-by-form-tab">
                                <form method="POST" action="{{route('etudiant_add_form')}}" class="row d-flex justify-content-center needs-validation" id="student-form">
                                    @CSRF
                                        <div class="col-md-7">
                                            <div class="row mb-3">
                                                <label for="matricule" class="col-sm-2 col-form-label">Matricule: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="7" maxlength="7" id="matricule" name="matricule" type="text" class="form-control" placeholder="Matricule de l'étudiant">
                                                    <div class="invalid-feedback">
                                                        Le matricule est requis et doit avoir 7 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="noms" class="col-sm-2 col-form-label">Noms: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="3" maxlength="60" id="noms" name="noms" type="text" class="form-control" placeholder="Noms et prénoms de l'étudiant">
                                                    <div class="invalid-feedback">
                                                        Le nom est requis et doit comprendre entre 3 et 60 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <label for="sexe" class="col-sm-4 col-form-label">Sexe: <span class="text-danger ql-size-huge">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select id="sexe" name="sexe" class="form-select" required>
                                                                <option hidden disabled selected="">De quel sexe est l'étudiant ?</option>
                                                                <option value="Masculin">Masculin</option>
                                                                <option value="Féminin">Féminin</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Le sexe est requis !
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <label for="date_naissance" class="col-sm-4 col-form-label">Date de naissance: <span class="text-danger ql-size-huge">*</span></label>
                                                        <div class="col-sm-8">
                                                            <input required id="date_naissance" name="date_naissance" type="date" class="form-control">
                                                            <div class="invalid-feedback">
                                                                La date de naissance est requise !
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code_classe" class="col-sm-2 col-form-label">Classe: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <select id="code_classe" name="code_classe" class="form-select" required>
                                                        <option selected="">De quelle classe est l'étudiant ?</option>
                                                        @foreach($classes as $classe)
                                                            <option value="{{$classe->code}}">{{$classe->code}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        La classe est requise !
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center">
                                                <button onclick="submitStudentForm()" type="submit" class="btn btn-outline-primary">Ajouter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- End Bordered Tabs Justified -->

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

@section('customs-scripts')
    <script src="{{ asset('assets/js/share.js') }}"></script>
    <script src="{{ asset('assets/js/importations/etudiants.js') }}"></script>
    <script>
    </script>
@endsection
