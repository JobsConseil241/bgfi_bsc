<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulaire extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id', 'id');
    }
}
