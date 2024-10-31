<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqStatistiques extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class, 'faq_no');
    }
}
