<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\ExerciceProgramme;


class ProgrammeController extends Controller
{
    public function index()
    {
        return Programme::all();
    }
    public function getProgrammesDisponibles(Request $req)
    {
        try {
            $req->validate([
                'exercice_id' => 'required|integer|min:0',
                'user_id' => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            //Recupère l erreur de validation des champs
            return response(['errors' => $e->errors()], 422);
        }
        $exercice_id = $req->input('exercice_id');
        $user_id = $req->input('user_id');


        $programmes = Programme::where(function ($query) use ($user_id) {
            $query->where('createur_id', null) //Programmes de base
                ->orWhere('createur_id', $user_id); //Programmes de l utilisateur
        })
            ->doesntHave('exercices', 'and', function ($query) use ($exercice_id) {
                $query->where('exercice_id', $exercice_id); // Exclure les programmes qui ont déjà l'exercice
            })
            ->get();

        return $programmes;
    }
    public function showExercicesPerformances(Request $req, $id)
    {
        try {
            $req->validate([
                'user_id' => 'required|integer|min:0',
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
    public function store(Request $req)
    {
        $programme = new Programme();
        try {
            $req->validate([
                'nom' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            //Recupère l erreur de validation des champs
            return response(['errors' => $e->errors()], 422);
        }

        $programme->nom = $req->input('nom');
        $programme->createur_id = 1; /**@todo rendre dynamique ça */
        $programme->save();
        return $programme;
    }

    public function destroy($id)
    {
        ExerciceProgramme::destroy($id);
        return response()->noContent();
    }
}