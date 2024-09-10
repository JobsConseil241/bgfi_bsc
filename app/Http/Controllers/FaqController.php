<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
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
        $data = Faq::all();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        //
    }
}
