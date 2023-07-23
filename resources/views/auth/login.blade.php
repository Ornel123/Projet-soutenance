@extends('layouts.app')

@section('customs-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/share.css') }}"/>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
            <div>
            <form method="post" action="{{ route('login') }}" class="row d-flex justify-content-center needs-validation" id="classe-form">
                                        @CSRF
                                        <div class="col-md-7">
                                            <div class="row mb-3">
                                                <label for="code">Email: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input :value="old('email')" required autofocus name="email" type="email" class="form-control" placeholder="Email du compte">
                                                    <div class="invalid-feedback">
                                                        Le code est requis et doit comprendre entre 2 et 15 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="code">Mot de passe: <span class="text-danger ql-size-huge">*</span></label>
                                                <div class="col-sm-10">
                                                    <input required minlength="3" maxlength="60" id="intitule" name="password" type="password" class="form-control" placeholder="Mot de passe">
                                                    <div class="invalid-feedback">
                                                        L'intitulé est requis et doit comprendre entre 3 et 60 caractères !
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-outline-primary">Login</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div style="color:red;font-size:12px;" class="row d-flex justify-content-center">
                                        <x-auth-validation-errors :errors="$errors" />
                                    </div>
            </div>
        </div>
        <div class="card-footer">Contacter l'admin pour la creation du compte</div>
    </div>
@endsection

@section('customs-scripts')
    <script src="{{ asset('assets/js/share.js') }}"></script>
    <script src="{{ asset('assets/js/importations/etudiants.js') }}"></script>
    <script>
    </script>
@endsection
