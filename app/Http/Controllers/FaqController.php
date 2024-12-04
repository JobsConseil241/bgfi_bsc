<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FaqController extends Controller
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
        $faqmngr = 'active';
        $title = 'FAQ - Gestion';
        $data = Faq::where('status', 1)->get();
        return view('dashboard.faq.index', compact('data', 'faqmngr', 'title'));
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
        $insertion = Faq::create([
            'titre' => $request->title,
            'stitre' => $request->subtitle,
            'status' =>1,
        ]);

        if($insertion){
            Session::flash('message', 'sauvegarde réussie!');
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
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $update = Faq::where('id',$request->id)->update([
            'titre' => $request->title,
            'status' =>1,
        ]);

        if($update){
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return redirect()->route('indexFAQs');
        } else{
            Session::flash('message', 'sauvegarde échoué, réessayer plutard!');
            Session::flash('status', 'danger');
            return redirect()->route('indexFAQs');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, $id){

        $update = Faq::where('id',$id)->update([
            'status' => 0
        ]);

        if($update){
            return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);
        } else{
            return response()->json(['status' => 500, 'danger' => 'Formulaire non soumis avec succès !']);
        }

    }
}
