<?php

namespace App\Http\Controllers;

use App\Models\Exercice;

use Illuminate\Http\Request;

class ExerciceController extends Controller
{
    public function index(Request $request)
    {
        $recherche = $request->input('recherche');
        $user_id = $request->input('user_id');

        //On recupère tous les exercices dont il a acces
        $exercices = Exercice::where(function ($query) use ($user_id, $recherche) {
            // Exercices globaux (sans createur)
            $query->where('createur_id', null);

            if ($user_id) {
                // Tous ses exercices
                $query->orWhere('createur_id', $user_id);
            }
        });

        if ($recherche) {
            //On filtre uniquement les exercices repondant a la recherche
            $exercices = $exercices->where('nom', 'like', '%' . $recherche . '%');
        }

        // // On ajoute les performances si on a un utilisateur
        // if ($user_id) {
        //     $exercices = $exercices->with([
        //         'performances' => function ($query) use ($user_id) {
        //             $query->where('user_id', $user_id)
        //                 ->orderBy('date_perf', 'desc');
        //         }
        //     ]);
        // }

        return $exercices->get();
    }
}