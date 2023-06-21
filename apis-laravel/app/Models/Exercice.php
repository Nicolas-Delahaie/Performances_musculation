<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function createur()
    {
        return $this->belongsTo(User::class, 'createur_id');
    }
    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
    public function muscles_travailles()
    {
        return $this->belongsToMany(Muscle::class, MuscleTravaille::class, 'exercice_id', 'muscle_id');
    }
    public function programmes()
    {
        return $this->belongsToMany(Programme::class, ExerciceProgramme::class, 'exercice_id', 'programme_id');
    }
}