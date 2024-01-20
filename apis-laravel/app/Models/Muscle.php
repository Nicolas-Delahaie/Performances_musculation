<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muscle extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function exercices()
    {
        return $this->belongsToMany(Exercice::class, MuscleTravaille::class, 'muscle_id', 'exercice_id');
    }
}