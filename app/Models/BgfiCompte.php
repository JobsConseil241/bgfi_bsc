<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BgfiCompte extends Model
{
    protected $table = 'bgfi_comptes';

    protected $fillable = [
        'numero_compte',
        'nom_complet',
        'telephone',
        'solde',
        'devise',
        'sexe',
    ];

    protected $casts = [
        'solde' => 'decimal:2',
    ];
}
