<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercice;
use App\Models\Programme;
use App\Models\ExerciceProgramme;

class ExerciceProgrammeController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Verification de la validite des cles etrangers
            $request->validate([
                'exercice_id' => 'required|integer|min:0',
                'programme_id' => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            //Recupère l erreur de validation des champs
            return response(['message' => 'Mauvais parametres', 'errors' => $e->errors()], 422);
        }

        $exercice_id = $request->input('exercice_id');
        $programme_id = $request->input('programme_id');

        // Verification de l existence des tables liees aux cles etrangeres
        Exercice::findOrFail($exercice_id);
        Programme::findOrFail($programme_id);


        // Verification de son existence
        $existeDeja = ExerciceProgramme::where('exercice_id', $exercice_id)
            ->where('programme_id', $programme_id)
            ->exists();
        if ($existeDeja) {
            return response(['message' => 'Liaison deja existante'], 422);
        }


        // Enregistrement
        $nouvelleLiaison = new ExerciceProgramme([
            'exercice_id' => $exercice_id,
            'programme_id' => $programme_id,
        ]);
        $nouvelleLiaison->save();

        return $nouvelleLiaison;
    }

    public function destroy(Request $request)
    {
        try {
            // Verification de la validite des cles etrangers
            $request->validate([
                'exercice_id' => 'required|integer|min:0',
                'programme_id' => 'required|integer|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            //Recupère l erreur de validation des champs
            return response(['message' => 'Mauvais parametres', 'errors' => $e->errors()], 422);
        }

        // Verification de son existence
        $exerciceProgramme = ExerciceProgramme::where('exercice_id', $request->input('exercice_id'))
            ->where('programme_id', $request->input('programme_id'))
            ->first();

        if ($exerciceProgramme) {
            $exerciceProgramme->delete();
        }

        return response()->noContent();
    }
}