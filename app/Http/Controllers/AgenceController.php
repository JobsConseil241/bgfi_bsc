<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AgenceController extends Controller
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
        $ags = 'active';
        $title = 'Gestion Agences';
        $data = Agence::where('status', 1)->get();
        return view('dashboard.agences.index', compact('data', 'ags', 'title'));
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

        $insertion = Agence::create([
            'libelle' => $request->nom,
            'has_faq' => $request->faq,
            'has_consult' => $request->consult,
            'has_reclame' => $request->recla,
            'has_avis' => $request->avis,
            'delais' => $request->delay,
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->route('indexAgences');
        } else{

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Agence $agence)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agence $agence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $update = Agence::where('id',$request->id)->update([
            'libelle' => $request->nome,
            'has_faq' => $request->faqe,
            'has_consult' => $request->consulte,
            'has_reclame' => $request->reclae,
            'has_avis' => $request->avise,
            'delais' => $request->delaye,
            'status' =>1,
        ]);

        if($update){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->route('indexAgences');
        } else{

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $update = Agence::where('id',$id)->update([
            'status' => 0
        ]);

        if($update){
            return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);
        } else{
            return response()->json(['status' => 500, 'danger' => 'Formulaire non soumis avec succès !']);
        }
    }
}
