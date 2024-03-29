@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Niveaux</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">Importations</li>
                <li class="breadcrumb-item active">Niveaux</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div id="import-container">
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
                        <div class="card-body">
                            <h5 class="card-title">Les differents Niveau</h5>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">N°</th>
                                            <th scope="col">Code</th>
                                            <th scope="col">Intitulé</th>
                                            <th scope="col">Retirer</th>
                                        </tr>
                                        </thead>
                                        <tbody id="levels-result">
                                            @foreach($niveaux as $niv)
                                                <tr>
                                                    <td>{{$niv->id}}</td>
                                                    <td>{{$niv->code}}</td>
                                                    <td>{{$niv->intitule}}</td>
                                                    <td>
                                                        <form action="{{route('niveaux_delete',$niv->id)}}" method="post">
                                                            @CSRF
                                                            <button class="btn btn-outline-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$niveaux->links()}}
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
                                Ajouter des niveau
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
                                                <input class="form-control" type="file" id="formFile" name="niveau" required accept=".xlsx,.csv" />
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
                                    <form method="POST" action="{{route('niveaux_add_form')}}" class="row d-flex justify-content-center needs-validation" id="level-form" >
                                        @CSRF
                                        <div class="col-md-7">
                                            <div class="row mb-3">
                                                <label for="code" class="col-sm-2 col-form-label">Code: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="2" maxlength="15" id="code" name="code" type="text" class="form-control" placeholder="Code du niveau">
                                                    <div class="invalid-feedback">
                                                        Le code est requis et doit comprendre entre 2 et 15 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code" class="col-sm-2 col-form-label">Intitulé: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="3" maxlength="60" id="intitule" name="intitule" type="text" class="form-control" placeholder="Intitulé du niveau">
                                                    <div class="invalid-feedback">
                                                        L'intitulé est requis et doit comprendre entre 3 et 60 caractères !
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
