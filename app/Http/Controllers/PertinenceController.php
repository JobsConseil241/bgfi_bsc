<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            ->where('s.agence_id', '<>', null)
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        return view('dashboard.pertinence.indexFAQ', compact('stats', 'ptnfaq', 'title', 'perti', 'agences'));
    }

    public function getVisitsFaq(Request $request){

        $val = $request->query('key');

        $stats = 0;

        if ($val == 0) {
            $stats = DB::table('faq_statistiques as s')
                ->join('faqs as f', 's.faq_no', '=', 'f.id')
                ->join('agences as a', 's.agence_id', '=', 'a.id')
                ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
                ->where('s.agence_id', '<>', null)
                ->groupBy('a.libelle','s.faq_no', 'f.titre')
                ->get();
        } else {
            $stats = DB::table('faq_statistiques as s')
                ->join('faqs as f', 's.faq_no', '=', 'f.id')
                ->join('agences as a', 's.agence_id', '=', 'a.id')
                ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
                ->where('s.agence_id', $val)
                ->groupBy('a.libelle','s.faq_no', 'f.titre')
                ->get();
        }


        return response()->json([
            'status' => 200,
            'response' => $stats,
        ]);
    }

    public function getVisitsFaqs(Request $request){

        $val = $request->query('key');

        $stats = 0;

        if ($val == 0) {
            $stats = DB::table('faq_statistiques as s')
                ->join('faqs as f', 's.faq_no', '=', 'f.id')
                ->join('agences as a', 's.agence_id', '=', 'a.id')
                ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
                ->where('s.agence_id', '<>', null)
                ->whereBetween('s.created_at', [$request->query('dateDebut'), $request->query('dateFin')])
                ->groupBy('a.libelle','s.faq_no', 'f.titre')
                ->get();
        } else {
            $stats = DB::table('faq_statistiques as s')
                ->join('faqs as f', 's.faq_no', '=', 'f.id')
                ->join('agences as a', 's.agence_id', '=', 'a.id')
                ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'), DB::raw('SUM(s.likes) as total_likes'), DB::raw('SUM(s.dislikes) as total_dislikes'))
                ->where('s.agence_id', $val)
                ->whereBetween('s.created_at', [$request->query('dateDebut'), $request->query('dateFin')])
                ->groupBy('a.libelle','s.faq_no', 'f.titre')
                ->get();
        }



        return response()->json([
            'status' => 200,
            'response' => $stats,
        ]);
    }
    public function indexAvis() {
        $formavis = 'active';
        $title = "Gestion Statistiques Avis";
        $perti = 'open';
        $ptnrcl = 'active';
        $agences = Agence::where('status', 1)->get();

        $statOff = DB::table('consultations as c')
            ->join('agences as a', 'c.agences_id', '=', 'a.id')
            ->select(
                'a.libelle as agence_name',
                'c.module as module',
                DB::raw('SUM(COALESCE(c.visite, 0)) as total_views'),
                DB::raw('SUM(COALESCE(c.interesse, 0)) as total_interet'),
                DB::raw('SUM(COALESCE(c.pas_interesse, 0)) as total_desinteret')
            )
            ->where('c.module', 'avis')
            ->groupBy('a.libelle', 'c.module')
            ->get();

        Log::info($statOff);



        return view('dashboard.pertinence.indexAvis', compact('statOff', 'formavis', 'title', 'ptnrcl', 'perti', 'agences'));
    }

    public function getVisitAvis(Request $request){

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

    public function getVisitsAvis(Request $request){

        $statOff = DB::table('consultations as c')
            ->join('agences as a', 'c.agences_id', '=', 'a.id')
            ->select(
                'a.libelle as agence_name',
                'c.module as module',
                DB::raw('SUM(COALESCE(c.visite, 0)) as total_views'),
                DB::raw('SUM(COALESCE(c.interesse, 0)) as total_interet'),
                DB::raw('SUM(COALESCE(c.pas_interesse, 0)) as total_desinteret')
            )
            ->where('c.module', 'avis')
            ->whereBetween('c.created_at', [$request->query('dateDebut'), $request->query('dateFin')])
            ->groupBy('a.libelle', 'c.module')
            ->get();

        Log::info($statOff);

        return response()->json([
            'status' => 200,
            'response' => $statOff,
        ]);
    }
    public function indexReclamatio() {
        $formavis = 'active';
        $title = "Gestion Statistiques Reclamation";
        $perti = 'open';
        $ptnavis = 'active';
        $agences = Agence::where('status', 1)->get();

        $statOff = DB::table('consultations as c')
            ->join('agences as a', 'c.agences_id', '=', 'a.id')
            ->select(
                'a.libelle as agence_name',
                'c.module as module',
                DB::raw('SUM(COALESCE(c.visite, 0)) as total_views'),
                DB::raw('SUM(COALESCE(c.interesse, 0)) as total_interet'),
                DB::raw('SUM(COALESCE(c.pas_interesse, 0)) as total_desinteret')
            )
            ->where('c.module', 'reclamation')
            ->groupBy('a.libelle', 'c.module')
            ->get();

        Log::info($statOff);



        return view('dashboard.pertinence.indexReclamation', compact('statOff', 'formavis', 'title', 'ptnavis', 'perti', 'agences'));
    }

    public function getVisitReclae(Request $request){

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

    public function getVisitsReclaes(Request $request){

        $statOff = DB::table('consultations as c')
            ->join('agences as a', 'c.agences_id', '=', 'a.id')
            ->select(
                'a.libelle as agence_name',
                'c.module as module',
                DB::raw('SUM(COALESCE(c.visite, 0)) as total_views'),
                DB::raw('SUM(COALESCE(c.interesse, 0)) as total_interet'),
                DB::raw('SUM(COALESCE(c.pas_interesse, 0)) as total_desinteret')
            )
            ->where('c.module', 'reclamation')
            ->whereBetween('c.created_at', [$request->query('dateDebut'), $request->query('dateFin')])
            ->groupBy('a.libelle', 'c.module')
            ->get();

        Log::info($statOff);

        return response()->json([
            'status' => 200,
            'response' => $statOff,
        ]);
    }

    public function indexConsultation() {
        $ptnec = 'active';
        $title = "Gestion Statistiques Consultation";
        $perti = 'open';
        $agences = Agence::where('status', 1)->get();

        $stats = DB::table('consultations as c')
            ->join('agences as a', 'c.agences_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'c.module as module', DB::raw('SUM(c.visite) as total_views'))
            ->where('c.module', 'consultation')
            ->groupBy('a.libelle','c.module')
            ->get();

        return view('dashboard.pertinence.indexConsultation', compact('stats', 'ptnec', 'title', 'perti', 'agences'));
    }

    public function getVisitsConsultations(Request $request){

        $stats = DB::table('consultations as c')
            ->join('agences as a', 'c.agences_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'c.module as module', DB::raw('SUM(c.visite) as total_views'))
            ->where('c.module', 'consultation')
            ->whereBetween('c.created_at', [$request->query('dateDebut'), $request->query('dateFin')])
            ->groupBy('a.libelle','c.module')
            ->get();

        return response()->json([
            'status' => 200,
            'response' => $stats,
        ]);
    }
}
