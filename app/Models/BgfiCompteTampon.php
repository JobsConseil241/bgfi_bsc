<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BgfiCompteTampon extends Model
{
    protected $table = 'bgfi_comptes_tampon';

    public $timestamps = false;

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
