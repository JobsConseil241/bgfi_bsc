<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PertinenceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexFaq() {
        $ptnfaq = 'active';
        $title = "Gestion Statistiques FAQs";
        $perti = 'open';
        $agences = Agence::where('status', 1)->get();

        $stats = DB::table('faq_statistiques as s')
            ->join('faqs as f', 's.faq_no', '=', 'f.id')
            ->join('agences as a', 's.agence_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
            ->where('s.agence_id', 1)
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        return view('dashboard.pertinence.indexFAQ', compact('stats', 'ptnfaq', 'title', 'perti', 'agences'));
    }

    public function getVisitsFaq(Request $request){

        $val = $request->query('key');

        $stats = DB::table('faq_statistiques as s')
            ->join('faqs as f', 's.faq_no', '=', 'f.id')
            ->join('agences as a', 's.agence_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
            ->where('s.agence_id', $val)
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        return response()->json([
            'status' => 200,
            'response' => $stats,
        ]);
    }

    public function getVisitsFaqs(Request $request){

        $val = $request->query('key');

        $stats = DB::table('faq_statistiques as s')
            ->join('faqs as f', 's.faq_no', '=', 'f.id')
            ->join('agences as a', 's.agence_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
            ->where('s.agence_id', $val)
            ->whereBetween('s.created_at', [$request->query('dateDebut'), $request->query('dateFin')])
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        return response()->json([
            'status' => 200,
            'response' => $stats,
        ]);
    }

    public function indexConsultation() {
        $ptnec = 'active';
        $title = "Gestion Statistiques Consultation";
        $perti = 'open';
        $agences = Agence::where('status', 1)->get();

        $stats = DB::table('consultations as c')
            ->join('agences as a', 'c.agence_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'c.module as module', DB::raw('SUM(c.visite) as total_views'))
            ->where('c.agence_id', 1)
            ->where('c.module', 'consultation')
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        return view('dashboard.pertinence.indexFAQ', compact('stats', 'ptnec', 'title', 'perti', 'agences'));
    }
}
