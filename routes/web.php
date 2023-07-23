<?php

use App\Http\Controllers\AdminController;
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
})->name('home')->middleware('auth');

Route::group([
       'prefix' => 'importations',
       'middleware' => 'auth'
    ], function($router){

    Route::group([
        'prefix' => 'filieres'
    ], function($router){
        Route::get('/', [FiliereController::class, 'view_index'])->name('filieres');
        Route::post('/', [FiliereController::class, 'add_filiere'])->name('filieres_add');
        Route::post('/form', [FiliereController::class, 'add_filiere_form'])->name('filieres_add_form');
        Route::post('/delete/{filId}', [FiliereController::class, 'delete_filiere'])->name('filieres_delete');
    });
    Route::group([
        'prefix' => 'niveaux'
    ], function($router){
        Route::get('/', [NiveauController::class, 'view_index'])->name('niveaux');
        Route::post('/', [NiveauController::class, 'add_niveau'])->name('niveaux_add');
        Route::post('/form', [NiveauController::class, 'add_niveau_form'])->name('niveaux_add_form');
        Route::post('/delete/{nivId}', [NiveauController::class, 'delete_niv'])->name('niveaux_delete');
    });
    Route::group([
        'prefix' => 'classes'
    ], function($router){
        Route::get('/', [ClasseController::class, 'view_index'])->name('classes.all');
        Route::post('/', [ClasseController::class, 'add_classe'])->name('classe_add');
        Route::post('/form', [ClasseController::class, 'add_classe_form'])->name('classe_form_add');
        Route::post('/delete/{classid}', [ClasseController::class, 'destroy'])->name('classe_delete');
    });
    Route::group([
        'prefix' => 'etudiants'
    ], function($router){
        Route::get('/', [EtudiantController::class, 'view_index'])->name('etudiants');
        Route::post('/', [EtudiantController::class, 'add_etudiant'])->name('etudiant_add');
        Route::post('/form', [EtudiantController::class, 'store'])->name('etudiant_add_form');
        Route::post('/delete/{etudiantId}', [EtudiantController::class, 'destroy'])->name('etudiant_delete');
    });
    Route::group([
        'prefix' => 'ues'
    ], function($router){
        Route::get('/', [UEController::class, 'view_index'])->name('ues');
        Route::post('/', [UEController::class, 'add_ue'])->name('ue_add');
        Route::post('/form',[UEController::class, 'add_ueForm'])->name('ue_form_add');
        Route::post('/delete/{ueId}', [UEController::class, 'destroy'])->name('ue_delete');
    });
    Route::group([
        'prefix' => 'notes'
    ], function($router){
        Route::get('/', [NoteController::class, 'view_index'])->name('notes');
        Route::get('/{noteId}', [NoteController::class, 'show'])->name('notes_edit');
        Route::put('/{noteId}', [NoteController::class, 'update_note']);
        Route::post('/', [NoteController::class, 'add_notes'])->name('notes_add');
        Route::post('/form',[NoteController::class, 'add_notes_form'])->name('notes_form_add');
        Route::post('/delete/{noteId}', [NoteController::class, 'destroy'])->name('notes_delete');
    });
    Route::group([
        'prefix' => 'calculmoyenne'
    ], function($router){
        Route::get('/', [CalculMoyenneController::class, 'view_index'])->name('calculmoyenne');
        Route::post('/', [CalculMoyenneController::class, 'calculate'])->name('calculmoyenne_spec');
    });
    Route::group([
        'prefix' => 'deliberation'
    ], function($router){
        Route::get('/', [DeliberationController::class, 'view_index'])->name('deliberation');
    });
});

Route::get('admin/admincreate', [AdminController::class, 'newAdmin'])->name('admin.admincreate');
Route::get('admin/profcreate', [AdminController::class, 'newProf'])->name('admin.profcreate');
Route::post('admin/admincreate', [AdminController::class, 'newAdminForm']);
Route::post('admin/profcreate', [AdminController::class, 'newProfForm']);



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
