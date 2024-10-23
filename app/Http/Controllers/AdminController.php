<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Consultation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Extract module names and statistics
        $modules = $consultations->pluck('module');
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
            $module = $consul->module; // Get the module name
            $totalVisits = $consul->total_visits;

            // Group by agency name and prepare data for each module
            $chartsData[$agencyName][] = [
                'module' => $module,
                'total_visits' => $totalVisits,
            ];
        }

        return view('dashboard.index', compact('title', 'modules', 'visites', 'interesses', 'pas_interesses', 'chartsData'));
    }

    /**
     * Display a listing of the resource.
     */
    public function indexUsers()
    {
        $usermng = 'active';
        $title = "Users - management";
        $users =  User::all();
        return view('dashboard.users.index', compact('title', 'users', 'usermng'));
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
