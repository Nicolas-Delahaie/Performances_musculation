<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    use HasFactory;
    public $timestamps = false;

    function performances()
    {
        return $this->hasMany(Performance::class);
    }
}