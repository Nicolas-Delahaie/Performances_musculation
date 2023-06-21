<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;
    public $timestamps = false;

    public $fillable = [
        'date_perf',
        'repetitions',
        'charge',
        'exercice_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function exercice()
    {
        return $this->belongsTo(Exercice::class);
    }
}