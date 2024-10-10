<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Faq;
use App\Models\Formulaire;
use App\Models\ReponseFaq;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        return view('welcome', compact('agence'));
    }

    public function indexFAQ(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();
        $Faq = Faq::all();

        foreach ($Faq as $faq) {
            $faq['reponses'] = ReponseFaq::where('id_faq', $faq->id)->get();
        }


        return view('dashboard.faq.view', compact('agence', 'Faq'));
    }
    public function indexReclamation(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();
        $formulaire = Formulaire::where('agence_id', $agence->id)->get();

        foreach ($Faq as $faq) {
            $faq['reponses'] = ReponseFaq::where('id_faq', $faq->id)->get();
        }


        return view('dashboard.faq.view', compact('agence', 'Faq'));
    }
}
