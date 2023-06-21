<?php

namespace App\Http\Controllers;

use App\Models\Exercice;

use Illuminate\Http\Request;

class ExerciceController extends Controller
{
    public function index(Request $request)
    {
        return Exercice::where('createur_id', $request->input('user_id'))->orWhereNull("createur_id")->get();
    }
}