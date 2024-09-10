<?php

namespace App\Http\Controllers;

use App\Models\ChampFormulaire;
use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ChampFormulaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function indexAvise($id)
    {
        $formavis = 'active';
        $title = "Gestion Formulaires";
        $champs = ChampFormulaire::where('id_formulaire', $id)->get();
        $formulaire =  Formulaire::where('id', $id)->first();
        $count = $champs->count();
        $form = 'open';

        return view('dashboard.formulaire.champAvis', compact('champs', 'count', 'title', 'formavis', 'formulaire', 'form'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAvise(Request $request, $id)
    {
        $insertion = ChampFormulaire::create([
            'intitulé' => $request->libelle,
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->requis,
            'position' => $request->position,
            'id_formulaire' => $id,
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
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editAvise(Request $request, $id)
    {
        $update = ChampFormulaire::where('id',$request->id)->update([
            'intitulé' => $request->libelle,
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->requis,
            'position' => $request->position,
            'id_formulaire' => $id,
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
    public function indexReclae($id)
    {
        $formrecla = 'active';
        $title = "Gestion Formulaires";
        $champs = ChampFormulaire::where('id_formulaire', $id)->get();
        $formulaire =  Formulaire::where('id', $id)->first();
        $count = $champs->count();
        $form = 'open';

        return view('dashboard.formulaire.champRecla', compact('champs', 'count', 'title', 'formrecla', 'formulaire', 'form'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReclae(Request $request, $id)
    {
        $insertion = ChampFormulaire::create([
            'intitulé' => $request->libelle,
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->requis,
            'position' => $request->position,
            'id_formulaire' => $id,
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
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editReclae(Request $request, $id)
    {
        $update = ChampFormulaire::where('id',$request->id)->update([
            'intitulé' => $request->libelle,
            'name' => $request->name,
            'type' => $request->type,
            'is_required' => $request->requis,
            'position' => $request->position,
            'id_formulaire' => $id,
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
    public function show(ChampFormulaire $champFormulaire)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChampFormulaire $champFormulaire)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChampFormulaire $champFormulaire)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChampFormulaire $champFormulaire)
    {
        //
    }
}
