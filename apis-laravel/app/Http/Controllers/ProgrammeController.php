<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;

class ProgrammeController extends Controller
{
    function index()
    {
        return Programme::all();
    }

    function showExercicesPerformances(Request $req, $id)
    {
        try {
            $req->validate([
                'user_id' => 'required|integer|min:0|max:65535',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            //Recupère l erreur de validation des champs
            return response(['errors' => $e->errors()], 422);
        }

        return Programme::findOrFail($id)
            ->exercices()
            ->with([
                'performances' => function ($query) use ($req) {
                    $query->where('user_id', $req->input('user_id'))
                        ->orderBy('date_perf', 'desc');
                }
            ])
            ->get();
    }

}