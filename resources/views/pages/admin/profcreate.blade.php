@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Creer un nouveau Enseignant</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Accueil</a></li>
                <li class="breadcrumb-item">admin</li>
                <li class="breadcrumb-item active">creer</li>
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
        <div class="card-header">Admin</div>
        <div class="card-body">
            <div>
            <form method="post" class="row d-flex justify-content-center needs-validation" id="classe-form">
                                        @CSRF
                                        <div class="col-md-7">
                                        <div class="row mb-3">
                                                <label for="code">Email: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required autofocus name="profemail" type="email" class="form-control" placeholder="Email du compte">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code">Nom: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required name="profnom" type="text" class="form-control" placeholder="Email du compte">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code">Mot de passe: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="3" maxlength="60" id="intitule" name="profpassword" type="password" class="form-control" placeholder="Mot de passe">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code">UE<span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                        <select required name="ueId" id="" class="form-control">
                                                            @foreach($ues as $ue)
                                                                <option value="{{$ue->id}}">{{$ue->intitule}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-outline-primary">Creer</button>
                                            </div>
                                        </div>
                                    </form>
            </div>
        </div>
        <div class="card-footer">creation du compte</div>
    </div>


@endsection

@section('customs-scripts')
    <script src="{{ asset('assets/js/share.js') }}"></script>
    <script src="{{ asset('assets/js/importations/etudiants.js') }}"></script>
    <script>
    </script>
@endsection
