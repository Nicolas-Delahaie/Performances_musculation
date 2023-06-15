<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuscleTravaille extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'muscles_travailles'; // On precise vu que par defaut il cherche la table "muscle_travailles"
}