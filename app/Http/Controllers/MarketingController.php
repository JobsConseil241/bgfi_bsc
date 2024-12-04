<?php

namespace App\Http\Controllers;

use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $mkrt = 'active';
        $title = 'Gestion Marketing';
        $data = Marketing::where('active', 1)->get();

        return view('dashboard.marketing.index', compact('data', 'mkrt', 'title'));
    }

    public function create(Request $request) {

//        dd($request->file('image'));

        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Store the file
        if ($request->file('image')) {
            $filePath = $request->file('image')->store('uploads', 'public');
            $datat = Marketing::create([
                'thumb_url' => $filePath,
                'active' => 1,
            ]);
            Session::flash('message', 'sauvegarde réussie!');
            Session::flash('status', 'success');
            return back();
        }

        Session::flash('message', 'sauvegarde echoué!');
        Session::flash('status', 'error');
        return back()->withErrors('File upload failed.');
    }

    public function delete(Request $request, $id){

        $update = Marketing::where('id',$id)->update([
            'active' => 0
        ]);

        if($update){
            return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);
        } else{
            return response()->json(['status' => 500, 'danger' => 'Formulaire non soumis avec succès !']);
        }

    }

    public function getVisits(Request $request){

        $val = $request->query('key');

        $stats = DB::table('faq_statistiques as s')
            ->join('faqs as f', 's.faq_no', '=', 'f.id')
            ->join('agences as a', 's.agence_id', '=', 'a.id')
            ->select('a.libelle as agence_name', 'f.titre as faq_name', 's.faq_no', DB::raw('SUM(s.view) as total_views'))
            ->where('s.agence_id', $val)
            ->groupBy('a.libelle','s.faq_no', 'f.titre')
            ->get();

        return response()->json([
            'status' => 200,
            'response' => $stats,
        ]);
    }
}
