<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Consultation;
use App\Models\Faq;
use App\Models\FaqStatistiques;
use App\Models\ReponseAvis;
use App\Models\ReponseReclamation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "BGFI Corner";
        $consultations = Consultation::select('module', 'visite', 'interesse', 'pas_interesse')->get();

        $no_faqs = count(Faq::where('status', 1)->get());
        $no_agences = count(Agence::all());
        $agences = Agence::where('status', 1)->get();
        $no_res_avis = ReponseAvis::groupBy('sender_no')->select('sender_no', DB::raw('count(*) as total'))->get();
        $no_res_recla = ReponseReclamation::groupBy('sender_no')->select('sender_no', DB::raw('count(*) as total'))->get();

        $data_ad = DB::table('formulaires')
            ->join('agences', 'formulaires.agence_id', '=', 'agences.id')
            ->select('agences.libelle as agence_libelle', 'formulaires.type', DB::raw('COUNT(*) as formulaire_count'))
            ->groupBy('agences.libelle', 'formulaires.type')
            ->get();

        $agencies = FaqStatistiques::with('agence')
            ->select('agence_id', 'faq_no', 'likes')
            ->whereIn('likes', function($query) {
                $query->select(DB::raw('MAX(likes)'))
                    ->from('faq_statistiques as subquery')
                    ->whereColumn('subquery.agence_id', 'faq_statistiques.agence_id')
                    ->groupBy('agence_id');
            })
            ->get()
            ->map(function ($stat) {
                return [
                    'nom' => $stat->agence->libelle ?? 'Nom indisponible',
                    'likes' => $stat->likes,
                    'faq' => $stat->faq->titre ?? 'FAQ sans titre',
                ];
            });

        $agencyNames = $agencies->pluck('nom');
        $likes = $agencies->pluck('likes');
        $faqTitles= $agencies->pluck('faq');

         $agencie = FaqStatistiques::with('agence')
             ->select('agence_id', 'faq_no', 'dislikes')
             ->whereIn('dislikes', function($query) {
                 $query->select(DB::raw('MAX(dislikes)'))
                     ->from('faq_statistiques as subquery')
                     ->whereColumn('subquery.agence_id', 'faq_statistiques.agence_id')
                     ->groupBy('agence_id');
             })
             ->get()
             ->map(function ($stat) {
                 return [
                     'nom' => $stat->agence->libelle ?? 'Nom indisponible',
                     'dislikes' => $stat->dislikes,
                     'faq' => $stat->faq->titre ?? 'FAQ sans titre',
                 ];
             });

        $stats = DB::table('faq_statistiques as s')
            ->join('faqs as f', 's.faq_no', '=', 'f.id')
            ->join('agences as a', 's.agence_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'))
            ->where('s.agence_id', 1)
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        $agencyName = $agencie->pluck('nom');
        $lik = $agencie->pluck('dislikes');
        $faqTitle = $agencie->pluck('faq');


        // Extract module names and statistics
        $modules = $consultations->pluck('module');

        for($i = 0; $i < count($modules); $i++) {
            $modules[$i] = ucfirst($modules[$i]);
        }

        $visites = $consultations->pluck('visite');
        $interesses = $consultations->pluck('interesse');
        $pas_interesses = $consultations->pluck('pas_interesse');

        $consultation = Consultation::with('agence')
            ->select('agences_id', 'module', \DB::raw('SUM(visite) as total_visits'))
            ->groupBy('agences_id', 'module')
            ->get();

        // Group data by agency for charts
        $chartsData = [];
        foreach ($consultation as $consul) {
            $agencyName = $consul->agence->libelle; // Get the agency name
            $module = ucfirst($consul->module); // Get the module name
            $totalVisits = $consul->total_visits;

            // Group by agency name and prepare data for each module
            $chartsData[$agencyName][] = [
                'module' => $module,
                'total_visits' => $totalVisits,
            ];
        }

        return view('dashboard.index', compact('title', 'stats', 'agences', 'modules', 'data_ad', 'agencies', 'agencyNames', 'likes', 'faqTitles',  'agencyName', 'lik', 'faqTitle',  'visites', 'interesses', 'pas_interesses', 'chartsData', 'no_agences', 'no_faqs', 'no_res_avis', 'no_res_recla'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexUsers()
    {
        $usermng = 'active';
        $title = "Users - management";
        $users =  User::where('status', 1)->get();
        return view('dashboard.users.index', compact('title', 'users', 'usermng'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function addUsers(Request $request)
    {
        $insertion = User::create([
            'name' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->mdp),
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->route('indexUsers');
        } else{

        }
    }
    public function editUsers(Request $request)
    {
        if ($request->mdp){
            $update = User::where('id',$request->id)->update([
                'name' => $request->nom,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->mdp),
                'status' =>1,
            ]);
        } else {
            $update = User::where('id',$request->id)->update([
                'name' => $request->nom,
                'email' => $request->email,
                'role' => $request->role,
                'status' =>1,
            ]);
        }

        if($update){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->route('indexUsers');
        } else{

        }
    }

    public function deleteUsers(Request $request, $id){

        $update = User::where('id',$id)->update([
            'status' => 0
        ]);

        if($update){
            return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);
        } else{
            return response()->json(['status' => 500, 'danger' => 'Formulaire non soumis avec succès !']);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
