@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Notes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">Importations</li>
                <li class="breadcrumb-item active">Notes</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-md-12">
                <div id="import-container">
                <div class="card">
                        <div class="card-body">
                                @if(Auth()->user()->role == "Enseignant")
                                    <h5 class="card-title">Les differents notes de <b>{{$theUe->code}}</b></h5>
                                    <div class="row">
                                <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Matricule</th>
                                        <th scope="col">Noms & Prénoms</th>
                                        <th scope="col">UE</th>
                                        <th scope="col">Note de CC</th>
                                        <th scope="col">Note de TP</th>
                                        <th scope="col">Note de SN</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="stored-notes-result">
                                        @foreach($notes as $note)
                                        <tr>
                                            <td>{{$note->id}}</td>
                                            <td>{{$note->etudiant->matricule}}</td>
                                            <td>{{$note->etudiant->noms}}</td>
                                            <td>{{$note->ue->intitule}}</td>
                                            <td>{{$note->cc}}</td>
                                            <td>{{$note->tp}}</td>
                                            <td>{{$note->sn}}</td>
                                            <td style="display:flex;justify-content:space-around;">
                                                <form action="{{route('notes_delete',$note->id)}}" method="post">
                                                                @CSRF
                                                                <button class="btn btn-outline-danger">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                </form>
                                                <a href="{{route('notes_edit',$note->id)}}">
                                                <button class="btn btn-outline-info">

                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                </div>
                            </div>
                                @else
                                    <h5 class="card-title">Les differents notes</h5>
                                    @foreach($classes as $cls)
                                    <h5 class="card-title">Notes de <b>{{$cls->filiere->intitule}} -> {{$cls->niveau->intitule}}</b>({{$cls->code}})</h5>
                                        @foreach($cls->ue as $singelUe)
                                        <h6>Notes de {{$singelUe->intitule}}</h6>
                                        <div class="row">
                                <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Matricule</th>
                                        <th scope="col">Noms & Prénoms</th>
                                        <th scope="col">UE</th>
                                        <th scope="col">Note de CC</th>
                                        <th scope="col">Note de TP</th>
                                        <th scope="col">Note de SN</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="stored-notes-result">
                                        @foreach($singelUe->notes as $note)
                                        <tr>
                                            <td>{{$note->id}}</td>
                                            <td>{{$note->etudiant->matricule}}</td>
                                            <td>{{$note->etudiant->noms}}</td>
                                            <td>{{$note->ue->intitule}}</td>
                                            <td>{{$note->cc}}</td>
                                            <td>{{$note->tp}}</td>
                                            <td>{{$note->sn}}</td>
                                            <td style="display:flex;justify-content:space-around;">
                                                <form action="{{route('notes_delete',$note->id)}}" method="post">
                                                                @CSRF
                                                                <button class="btn btn-outline-danger">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                </form>
                                                <a href="{{route('notes_edit',$note->id)}}">
                                                <button class="btn btn-outline-info">

                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                                        @endforeach
                                    @endforeach
                                @endif

                            <div class="pagination">
                                </div>
                        </div>
                    </div>
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
                            <h5 class="card-title" style="position: relative;">
                                <button onclick="showSummaryContainer()" style="position: absolute; right: 0;" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-arrow-left-square"></i>
                                </button>
                                @if(Auth()->user()->role == "Enseignant")
                                    Ajouter des notes de <b>{{$theUe->code}}</b>
                                @else
                                    Les Notes
                                @endif
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
                                        <div>
                                            <form
                                            enctype="multipart/form-data"
                                            method="POST"
                                            style="display:flex;
                                            justify-content:space-around;
                                            flex-direction:column;
                                            gap:10px;">
                                                @CSRF
                                                <div class="col-sm-12">
                                                    @if(Auth()->user()->role == "Admin")
                                                <select id="ueCode" name="ueCode" class="form-select" required>
                                                    <option disabled selected hidden value="">De quelle UE est la note ?</option>
                                                    @foreach($ues as $ue)
                                                        <option value="{{$ue->code}}">{{$ue->code}}</option>
                                                    @endforeach
                                                </select>
                                                    <div class="invalid-feedback">
                                                    L'UE est requise !
                                                    </div>
                                                    @else
                                                    <input type="text" name="ueCode" value="{{$theUe->code}}" readonly class="form-control">
                                                    @endif

                                                </div>
                                                <input class="form-control" type="file" id="formFile" name="notes" required accept=".xlsx,.csv" />
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
                                <form method="post" action="{{route('notes_form_add')}}" class="row d-flex justify-content-center needs-validation" id="note-form">
                                    @CSRF
                                        <div class="row mb-3">
                                                <label for="matricule_etudiant" class="col-sm-2 col-form-label">UE: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    @if(Auth()->user()->role == "Admin")
                                                <select id="ueCode" name="ueCode" class="form-select" required>
                                                    <option disabled selected hidden value="">De quelle UE est la note ?</option>
                                                    @foreach($ues as $ue)
                                                        <option value="{{$ue->code}}">{{$ue->code}}</option>
                                                    @endforeach
                                                </select>
                                                    <div class="invalid-feedback">
                                                    L'UE est requise !
                                                    </div>
                                                    @else
                                                    <input type="text" name="ueCode" value="{{$theUe->code}}" readonly class="form-control">
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="matricule_etudiant" class="col-sm-2 col-form-label">Matricule: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="7" maxlength="7" id="matricule_etudiant" name="matricule_etudiant" type="text" class="form-control" placeholder="Matricule de l'étudiant">
                                                    <div class="invalid-feedback">
                                                        Le matricule est requis et doit avoir 7 caractères !
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="note" class="col-sm-2 col-form-label">Note CC: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required min="0" max="20" id="note" name="notecc" type="number" class="form-control" placeholder="Note de l'étudiant">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="note" class="col-sm-2 col-form-label">Note TP: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input min="0" max="40" id="notetp" name="notetp" type="number" class="form-control" placeholder="Note de l'étudiant">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="note" class="col-sm-2 col-form-label">Note SN: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input min="0" max="40" id="notesn" name="notesn" type="number" class="form-control" placeholder="Note de l'étudiant">
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
