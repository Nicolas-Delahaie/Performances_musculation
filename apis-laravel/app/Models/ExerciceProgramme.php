<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciceProgramme extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'exercices_programmes'; // On precise vu que par defaut il cherche la table "exercice_programmes"

    public $fillable = [
        'exercice_id',
        'programme_id',
    ];
}