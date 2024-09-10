<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
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
        $pagema = 'active';
        $data = Setting::all();
        $agences = Agence::all();
        $title = "Paramètres";
        return view('dashboard.settings.index', compact('data', 'pagema', 'title', 'agences'));
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
        $request->validate([
            'logo' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = Setting::where('agence_id', $request->id_agence)->first();

        if ($data){
            $fileName = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'logo_accueil.' . strtolower($extension);
                $file->move(public_path('settings'), $fileName);
            }

            Setting::where('agence_id',$request->id_agence)->update([
                'titre' => $request->titre_accueil,
                'stitre' => $request->sous_titre,
                'titre_service' => $request->title_service,
                'logo' => $fileName,
                'agence_id' => $request->id_agence
            ]);

            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();

        } else {
            $fileName = null;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'logo_accueil.' . strtolower($extension);
                $file->move(public_path('settings'), $fileName);
            }

            Setting::create([
                'titre' => $request->titre_accueil,
                'stitre' => $request->sous_titre,
                'titre_service' => $request->title_service,
                'logo' => $fileName,
                'agence_id' => $request->id_agence,
            ]);

            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();
        }

        Session::flash('message', 'Échec du téléchargement du fichier.!');
        Session::flash('status', 'danger');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeFaq(Request $request)
    {
        $request->validate([
            'logoFAQ' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = Setting::where('agence_id', $request->id_agence)->first();

        if ($data){
            $fileName = null;

            if ($request->hasFile('logoFAQ')) {
                $file = $request->file('logoFAQ');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'logo_faq.' . strtolower($extension);
                $file->move(public_path('settings'), $fileName);
            }

            Setting::where('agence_id',$request->id_agence)->update([
                'faq_titre' => $request->titre_faq,
                'faq_stitre' => $request->sous_titre_faq,
                'faq_logo' => $fileName,
            ]);

            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();

        } else {
        }

        Session::flash('message', 'Échec du téléchargement du fichier.!');
        Session::flash('status', 'danger');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeConsultation(Request $request)
    {
        $request->validate([
            'logoConsult' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = Setting::where('agence_id', $request->id_agence)->first();

        if ($data){
            $fileName = null;

            if ($request->hasFile('logoConsult')) {
                $file = $request->file('logoConsult');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'logo_consultation.' . strtolower($extension);
                $file->move(public_path('settings'), $fileName);
            }

            Setting::where('agence_id',$request->id_agence)->update([
                'consult_titre' => $request->titre_consult,
                'consult_stitre' => $request->sous_titre_consult,
                'consult_logo' => $fileName,
            ]);

            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();

        } else {
        }

        Session::flash('message', 'Échec du téléchargement du fichier.!');
        Session::flash('status', 'danger');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAvis(Request $request)
    {
        $request->validate([
            'logoAvis' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = Setting::where('agence_id', $request->id_agence)->first();

        if ($data){
            $fileName = null;

            if ($request->hasFile('logoAvis')) {
                $file = $request->file('logoAvis');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'logo_avis.' . strtolower($extension);
                $file->move(public_path('settings'), $fileName);
            }

            Setting::where('agence_id',$request->id_agence)->update([
                'avis_titre' => $request->titre_avis,
                'avis_stitre' => $request->sous_titre_avis,
                'avis_logo' => $fileName,
            ]);

            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();

        } else {
        }

        Session::flash('message', 'Échec du téléchargement du fichier.!');
        Session::flash('status', 'danger');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeReclamation(Request $request)
    {
        $request->validate([
            'logoRecla' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = Setting::where('agence_id', $request->id_agence)->first();

        if ($data){
            $fileName = null;

            if ($request->hasFile('logoRecla')) {
                $file = $request->file('logoRecla');
                $extension = $file->getClientOriginalExtension();
                $fileName = 'logo_reclamation.' . strtolower($extension);
                $file->move(public_path('settings'), $fileName);
            }

            Setting::where('agence_id',$request->id_agence)->update([
                'recla_titre' => $request->titre_recla,
                'recla_stitre' => $request->sous_titre_recla,
                'recla_logo' => $fileName,
            ]);

            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();

        } else {
        }

        Session::flash('message', 'Échec du téléchargement du fichier.!');
        Session::flash('status', 'danger');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->query('id');
        $setting_data = Setting::where('agence_id', $id)->first();

        return response()->json($setting_data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
