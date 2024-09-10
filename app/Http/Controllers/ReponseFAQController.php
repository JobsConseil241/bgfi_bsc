<?php

namespace App\Http\Controllers;

use App\Models\ReponseFAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReponseFAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function  store(Request $request)
    {
        $insertion = ReponseFAQ::create([
            'id_faq' => $request->id,
            'reponse' => $request->response,
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie, reponse ajoutée avec succès!');
            Session::flash('status', 'success');
            return redirect()->route('indexFAQs');
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return redirect()->route('indexFAQs');
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
    public function edit(ReponseFAQ $reponseFAQ)
    {
        //
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
    public function destroy(ReponseFAQ $reponseFAQ)
    {
        //
    }
}
