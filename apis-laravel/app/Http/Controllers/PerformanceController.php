<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Performance;
use \App\Models\Exercice;
use \App\Models\User;

class PerformanceController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Verification de la validite des cles etrangers
            $request->validate([
                'exercice_id' => 'required|integer|min:0',
                'user_id' => 'required|integer|min:0',
            ]);
            $exercice_id = $request->input('exercice_id');
            $user_id = $request->input('user_id');

            // Verification de l existence des tables liees aux cles etrangeres
            $exercice = Exercice::findOrFail($exercice_id);
            User::findOrFail($user_id);


            // Verification de la validite des autres parametres
            // On verifie la date et le nombre de rep
            $request->validate([
                // AJouter les max
                'date_perf' => 'required',
                'repetitions' => 'required|integer|min:0|max:65535',
                'charge' => 'integer|min:0|max:65535',
            ]);

            // Si c est en charge libre, il faut une charge
            if (!$exercice->poidsDeCorps) {
                $request->validate([
                    'charge' => 'required',
                ]);
            }

            // Enregistrement
            $nouvellePerformance = new Performance([
                'repetitions' => $request->input('repetitions'),
                'date_perf' => $request->input('date_perf'),
                'charge' => $request->input('charge'),
                'exercice_id' => $exercice_id,
                'user_id' => $user_id,
            ]);
            $nouvellePerformance->save();

            return $nouvellePerformance;
        } catch (\Illuminate\Validation\ValidationException $e) {
            //Recupère l erreur de validation des champs
            return response(['message' => 'Mauvais parametres', 'errors' => $e->errors()], 422);
        }
    }
}