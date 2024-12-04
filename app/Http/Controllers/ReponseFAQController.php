<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\ReponseFAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReponseFAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $faqID = Faq::where('id', $id)->first();
        $reponses =  ReponseFAQ::where('id_faq', $faqID->id)->get();
        $faqmngr = 'active';
        $title = 'FAQ - Gestion';

        return view('dashboard.faq.responses', compact('title', 'faqmngr', 'faqID', 'reponses'));

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
    public function  store(Request $request, $id)
    {
        $insertion = ReponseFAQ::create([
            'id_faq' => $id,
            'reponse' => $request->response,
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie, reponse ajoutée avec succès!');
            Session::flash('status', 'success');
            return  redirect()->back();
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return  redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReponseFAQ $reponseFAQ)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $update = ReponseFAQ::where('id',$request->id_response)->update([
            'id_faq' => $id,
            'reponse' => $request->response,
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReponseFAQ $reponseFAQ)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $update = ReponseFAQ::where('id',$id)->update([
            'status' => 0
        ]);

        if($update){
            return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);
        } else{
            return response()->json(['status' => 500, 'danger' => 'Formulaire non soumis avec succès !']);
        }
    }
}
