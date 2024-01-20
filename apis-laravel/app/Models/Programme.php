<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;

    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }
    public function exercices()
    {
        return $this->belongsToMany(Exercice::class, ExerciceProgramme::class, 'programme_id', 'exercice_id');
    }
}