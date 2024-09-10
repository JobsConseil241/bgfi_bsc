<?php

namespace App\Http\Controllers;

use App\Models\ChampFormulaire;
use App\Models\Formulaire;
use App\Models\OptionChamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OptionChampController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param $id
     * @param $champ
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function indexChampOption($id, $champ)
    {
        $formavis = 'active';
        $title = "Gestion Formulaires";
        $champs = ChampFormulaire::where('id', $champ)->first();
        $formulaire =  Formulaire::where('id', $id)->first();
        $option = OptionChamp::where('id_champ', $champ)->get();
        $count = $champs->count();
        $form = 'open';

        return view('dashboard.formulaire.optionsAvis', compact('champs', 'count', 'title', 'formavis', 'formulaire', 'form', 'option'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeChampOption(Request $request, $id, $champ)
    {
        $insertion = OptionChamp::create([
            'libelle' => $request->libelle,
            'description' => $request->description,
            'id_form' => $id,
            'id_champ' => $request->id_champ,
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
     * @param $champ
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editChampOption(Request $request, $id, $champ)
    {
        $update = OptionChamp::where('id',$request->id_opt)->update([
            'libelle' => $request->libellee,
            'description' => $request->descriptione,
            'id_form' => $id,
            'id_champ' => $request->id_champe,
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

    public function indexChampOptionRecla($id, $champ)
    {
        $formrecla = 'active';
        $title = "Gestion Formulaires";
        $champs = ChampFormulaire::where('id', $champ)->first();
        $formulaire =  Formulaire::where('id', $id)->first();
        $option = OptionChamp::where('id_champ', $champ)->get();
        $count = $champs->count();
        $form = 'open';

        return view('dashboard.formulaire.optionsRecla', compact('champs', 'count', 'title', 'formrecla', 'formulaire', 'form', 'option'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function storeChampOptionRecla(Request $request, $id, $champ)
    {
        $insertion = OptionChamp::create([
            'libelle' => $request->libelle,
            'description' => $request->description,
            'id_champ' => $request->id_champ,
            'id_form' => $id,
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
     * @param $champ
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editChampOptionRecla(Request $request, $id, $champ)
    {
        $update = OptionChamp::where('id',$request->id_opt)->update([
            'libelle' => $request->libellee,
            'description' => $request->descriptione,
            'id_champ' => $request->id_champe,
            'id_form' => $id,
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
     * Display the specified resource.
     */
    public function show(OptionChamp $optionChamp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OptionChamp $optionChamp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OptionChamp $optionChamp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OptionChamp $optionChamp)
    {
        //
    }
}
