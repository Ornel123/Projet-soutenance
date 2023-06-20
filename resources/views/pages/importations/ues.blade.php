@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Unités d'Enseignement</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">Importations</li>
                <li class="breadcrumb-item active">UEs</li>
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
                                            <th scope="col">Code</th>
                                            <th scope="col">Intitulé</th>
                                            <th scope="col">Classe</th>
                                            <th scope="col">Semestre</th>
                                            <th scope="col">Crédit</th>
                                            <th scope="col">Est optionnelle</th>
                                            <th scope="col">Possède TP</th>
                                            <th scope="col">Retirer</th>
                                        </tr>
                                        </thead>
                                        <tbody id="ues-result">
                                            @foreach($ues as $ue)
                                            <tr>
                                                <td>{{$ue->id}}</td>
                                                <td>{{$ue->code}}</td>
                                                <td>{{$ue->intitule}}</td>
                                                <td>{{$ue->classe->intitule}}</td>
                                                <td>{{$ue->semestre}}</td>
                                                <td>{{$ue->credit}}</td>
                                                <td>
                                                    @if($ue->ue_optionelle == 1)
                                                        Oui
                                                    @else
                                                        Non
                                                    @endif
                                                </td>
                                                <td>
                                                @if($ue->tp_optionel == 1)
                                                        Oui
                                                    @else
                                                        Non
                                                    @endif
                                                </td>
                                                <td>
                                                        <button class="btn btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$ues->links()}}
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
                                                <input class="form-control" type="file" id="formFile" name="ue_file" required accept=".xlsx,.csv" />
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
                                    <form action="{{route('ue_form_add')}}" method="POST" class="row d-flex justify-content-center needs-validation" id="ue-form">
                                        @CSRF
                                        <div class="col-md-7">
                                            <div class="row mb-3">
                                                <label for="code" class="col-sm-2 col-form-label">Code: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="2" maxlength="15" id="code" name="code" type="text" class="form-control" placeholder="Code de l'UE">
                                                    <div class="invalid-feedback">
                                                        Le code est requis et doit comprendre entre 2 et 15 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="intitule" class="col-sm-2 col-form-label">Intitulé: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="3" maxlength="60" id="intitule" name="intitule" type="text" class="form-control" placeholder="Intitulé de la l'UE">
                                                    <div class="invalid-feedback">
                                                        L'intitulé est requis et doit comprendre entre 3 et 60 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code_classe" class="col-sm-2 col-form-label">Classe: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <select id="code_classe" name="code_classe" class="form-select" required>
                                                        <option selected="">De quelle classe est l'ue ?</option>
                                                        @foreach($classes as $classe)
                                                            <option value="{{$classe->code}}">{{$classe->code}}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        La classe est requise !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <label for="semestre" class="col-sm-4 col-form-label">Semestre: <span class="text-danger ql-size-huge">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select id="semestre" name="semestre" class="form-select" required>
                                                                    <option hidden disabled selected="">De quel semestre est l'UE ?</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Ce champ est requis !
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <label for="credit" class="col-sm-4 col-form-label">Crédit: <span class="text-danger ql-size-huge">*</span></label>
                                                        <div class="col-sm-8">
                                                            <input required minlength="3" maxlength="60" id="credit" name="credit" type="number" class="form-control" placeholder="Nombre de crédits de l'UE">
                                                            <div class="invalid-feedback">
                                                                Le crédit est requis !
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <label for="tp_optionel" class="col-sm-4 col-form-label">TP requis: <span class="text-danger ql-size-huge">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select id="tp_optionel" name="tp_optionel" class="form-select" required>
                                                                <option hidden disabled selected="">L'UE possède-t-elle un TP</option>
                                                                <option value="false">Oui</option>
                                                                <option value="true">Non</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Ce champ est requis !
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <label for="ue_optionelle" class="col-sm-4 col-form-label">Option: <span class="text-danger ql-size-huge">*</span></label>
                                                        <div class="col-sm-8">
                                                            <select id="ue_optionelle" name="ue_optionelle" class="form-select" required>
                                                                <option hidden disabled selected="">L'UE est-elle optionnelle</option>
                                                                <option value="true">Oui</option>
                                                                <option value="false">Non</option>
                                                            </select>
                                                            <div class="invalid-feedback">
                                                                Ce champ est requis !
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-outline-primary">Ajouter</button>
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
@endsection
