<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        return view('welcome', compact('agence'));
    }

    public function indexFAQ(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();


        return view('dashboard.faq.view', compact('agence'));
    }
}
