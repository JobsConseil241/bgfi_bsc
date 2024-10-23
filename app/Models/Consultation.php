<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agences_id'); // 'agences_id' is the foreign key in the consultations table
    }
}
