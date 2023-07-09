<?php

use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function () {
    return Programme::find(1)->exercices;
});



// Programmes
use App\HTTP\Controllers\ProgrammeController;

Route::get('/programmes', [ProgrammeController::class, 'index']);
Route::get('/programmes/disponibles', [ProgrammeController::class, 'getProgrammesDisponibles']);
Route::get('/programmes/{id}/exercices/performances', [ProgrammeController::class, 'showExercicesPerformances']);
Route::post('/programmes', [ProgrammeController::class, 'store']);
Route::delete('/programmes/{id}', [ProgrammeController::class, 'destroy']);


// Performances
use App\HTTP\Controllers\PerformanceController;

Route::post('/performance', [PerformanceController::class, 'store']);


// Exercices
use App\HTTP\Controllers\ExerciceController;

Route::get('/exercices', [ExerciceController::class, 'index']);


// Exercices_Programmes
use App\HTTP\Controllers\ExerciceProgrammeController;

Route::post('/exercices_programmes', [ExerciceProgrammeController::class, 'store']);
Route::delete('/exercices_programmes', [ExerciceProgrammeController::class, 'destroy']);