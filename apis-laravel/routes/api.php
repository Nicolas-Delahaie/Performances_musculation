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
    return App\Models\Performance::find(1);
});
Route::get('/test2', function () {
    return App\Models\Programme::find(1)->exercices;
});


// Programmes
use App\HTTP\Controllers\ProgrammeController;

Route::get('/programmes', [ProgrammeController::class, 'index']);
Route::get('/programmes/{id}/exercices/performances', [ProgrammeController::class, 'showExercicesPerformances']);
Route::post('/programmes', [ProgrammeController::class, 'store']);

// Performances
use App\HTTP\Controllers\PerformanceController;

Route::post('/performance', [PerformanceController::class, 'store']);


// Exercices
use App\HTTP\Controllers\ExerciceController;

Route::get('/exercices', [ExerciceController::class, 'index']);