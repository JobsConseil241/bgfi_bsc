<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FormulaireController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function indexReclamation()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAvis()
    {
        $formavis = 'active';
        $datas = Formulaire::where('type', 'avis')->get();
        $title = "Gestion Formulaires";
        $agences = Agence::all();
        $form = 'open';

        foreach ($datas as $dt) {
            $dt->agence_id = Agence::where('id', $dt->agence_id)->first()->libelle;
        }

        return view('dashboard.formulaire.indexAvis', compact('datas', 'title', 'formavis', 'agences', 'form'));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function storeAvis(Request $request)
    {
        $insertion = Formulaire::create([
            'libelle' => $request->libelle,
            'description' => $request->description,
            'type' => $request->type,
            'agence_id' => $request->agence,
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->back();
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function editAvis(Request $request)
    {
        $update = Formulaire::where('id',$request->id)->update([
            'libelle' => $request->libelle,
            'description' => $request->description,
            'type' => $request->type,
            'agence_id' => $request->agence,
            'status' =>1,
        ]);

        if($update){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->back();
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function indexRecla()
    {
        $formrecla = 'active';
        $datas = Formulaire::where('type', 'reclamation')->get();
        $title = "Gestion Formulaires";
        $agences = Agence::all();
        $form = 'open';

        foreach ($datas as $dt) {
            $dt->agence_id = Agence::where('id', $dt->agence_id)->first()->libelle;
        }

        return view('dashboard.formulaire.indexRecla', compact('datas', 'title', 'formrecla', 'agences', 'form'));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function storeRecla(Request $request)
    {
        $insertion = Formulaire::create([
            'libelle' => $request->libelle,
            'description' => $request->description,
            'type' => $request->type,
            'agence_id' => $request->agence,
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->back();
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    public function editRecla(Request $request)
    {
        $update = Formulaire::where('id',$request->id)->update([
            'libelle' => $request->libellee,
            'description' => $request->descriptione,
            'type' => $request->typee,
            'agence_id' => $request->agencee,
            'status' =>1,
        ]);

        if($update){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->back();
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return redirect()->back();
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Formulaire $formulaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formulaire $formulaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Formulaire $formulaire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Formulaire $formulaire)
    {
        //
    }
}
