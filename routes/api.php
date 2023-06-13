<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UEController;
use App\Http\Controllers\CalculMoyenneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('filieres', FiliereController::class);
Route::resource('niveaux', NiveauController::class);
Route::resource('classes', ClasseController::class);
Route::resource('etudiants', EtudiantController::class);
Route::resource('ues', UEController::class);
Route::resource('notes', NoteController::class);
Route::resource('CalculMoyenne', CalculMoyenneController::class);
Route::get('CalculMoyenne', [CalculMoyenneController::class, 'show']);
Route::post('CalculMoyenne', [CalculMoyenneController::class, 'store']);
