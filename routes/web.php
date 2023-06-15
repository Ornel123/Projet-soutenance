<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UEController;
use App\Http\Controllers\CalculMoyenneController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return View::make('pages.home');
})->name('home');

Route::group([
       'prefix' => 'importations'
    ], function($router){

    Route::group([
        'prefix' => 'filieres'
    ], function($router){
        Route::get('/', [FiliereController::class, 'view_index'])->name('filieres');
        Route::post('/', [FiliereController::class, 'add_filiere'])->name('filieres_add');
    });
    Route::group([
        'prefix' => 'niveaux'
    ], function($router){
        Route::get('/', [NiveauController::class, 'view_index'])->name('niveaux');
        Route::post('/', [NiveauController::class, 'add_niveau'])->name('niveaux_add');
    });
    Route::group([
        'prefix' => 'classes'
    ], function($router){
        Route::get('/', [ClasseController::class, 'view_index'])->name('classes.all');
        Route::post('/', [ClasseController::class, 'add_classe'])->name('classe_add');
        Route::post('/form', [ClasseController::class, 'add_classe_form'])->name('classe_form_add');
    });
    Route::group([
        'prefix' => 'etudiants'
    ], function($router){
        Route::get('/', [EtudiantController::class, 'view_index'])->name('etudiants');
        Route::post('/', [EtudiantController::class, 'add_etudiant'])->name('etudiant_add');
        Route::post('/form', [EtudiantController::class, 'store'])->name('etudiant_add_form');
    });
    Route::group([
        'prefix' => 'ues'
    ], function($router){
        Route::get('/', [UEController::class, 'view_index'])->name('ues');
        Route::post('/', [UEController::class, 'add_ue'])->name('ue_add');
        Route::post('/form',[UEController::class, 'add_ueForm'])->name('ue_form_add');
    });
    Route::group([
        'prefix' => 'notes'
    ], function($router){
        Route::get('/', [NoteController::class, 'view_index'])->name('notes');
    });
    Route::group([
        'prefix' => 'calculmoyenne'
    ], function($router){
        Route::get('/', [CalculMoyenneController::class, 'view_index'])->name('calculmoyenne');
    });
    Route::group([
        'prefix' => 'deliberation'
    ], function($router){
        Route::get('/', [DeliberationController::class, 'view_index'])->name('deliberation');
    });
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
