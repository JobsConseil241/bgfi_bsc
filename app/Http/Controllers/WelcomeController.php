<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationReception;
use App\Models\Agence;
use App\Models\ChampFormulaire;
use App\Models\Consultation;
use App\Models\Faq;
use App\Models\FaqStatistiques;
use App\Models\Formulaire;
use App\Models\ReponseAvis;
use App\Models\ReponseFaq;
use App\Models\ReponseReclamation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class WelcomeController extends Controller
{
    public function index(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        return view('welcome', compact('agence'));
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function indexFAQ(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();
        $Faq = Faq::all();

        foreach ($Faq as $faq) {
            $faq['reponses'] = ReponseFaq::where('id_faq', $faq->id)->get();
        }

        $agence_id = Agence::where('libelle', $nom)->first()->id;
        $cons = Consultation::where('agences_id', $agence_id)->where('module', 'faq')->first();

        $visite = $cons ? $cons->visite : null;

        $consultation = Consultation::firstOrNew(['agences_id' => $agence_id, 'module' => 'faq']);
        $consultation->agences_id = $agence_id;
        $consultation->ip_address = $this->getClientIPaddress();
        $consultation->module = 'faq';
        $consultation->visite = $visite + 1 ;
        $consultation->save();


        return view('dashboard.faq.view', compact('agence', 'Faq'));
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function indexAvis(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        $form = Formulaire::where('agence_id', $agence->id)->where('type', 'avis')->first();

        // Récupérer les champs et leurs options associés via Eloquent
        $formFields = ChampFormulaire::with('options')
            ->where('id_formulaire', $form->id)
            ->orderBy('position', 'asc')
            ->get();

        $agence_id = Agence::where('libelle', $nom)->first()->id;
        $cons = Consultation::where('agences_id', $agence_id)->where('module', 'avis')->first();

        $visite = $cons ? $cons->visite : null;

        $consultation = Consultation::firstOrNew(['agences_id' => $agence_id, 'module' => 'avis', 'ip_address' => $this->getClientIPaddress()]);
        $consultation->agences_id = $agence_id;
        $consultation->ip_address = $this->getClientIPaddress();
        $consultation->module = 'avis';
        $consultation->visite = $visite + 1 ;
        $consultation->save();

        // Passer les champs à la vue
        return view('dashboard.formulaire.avisFormView', compact('formFields'));
    }
    public function indexReclamation(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        $form = Formulaire::where('agence_id', $agence->id)->where('type', 'reclamation')->first();

        // Récupérer les champs et leurs options associés via Eloquent
        $fields = ChampFormulaire::with('options')
            ->where('id_formulaire', $form->id)
            ->orderBy('position', 'asc')
            ->get();

        $agence_id = Agence::where('libelle', $nom)->first()->id;
        $cons = Consultation::where('agences_id', $agence_id)->where('module', 'reclamation')->first();

        $visite = $cons ? $cons->visite : null;

        $consultation = Consultation::firstOrNew(['agences_id' => $agence_id, 'module' => 'reclamation', 'ip_address' => $this->getClientIPaddress()]);
        $consultation->agences_id = $agence_id;
        $consultation->ip_address = $this->getClientIPaddress();
        $consultation->module = 'reclamation';
        $consultation->visite = $visite + 1 ;
        $consultation->save();

        // Passer les champs à la vue
        return view('dashboard.formulaire.reclamationFormView', compact('fields'));
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAvis(Request $request, $nom) {
        $data = $request->all();

        $numero = ReponseAvis::all()->groupBy('sender_no')->count() + 1;

        $formFields = [];
        $fieldIds = [];

        foreach ($data as $key => $value) {
            if ($key === '_token') {
                continue;
            }
            if (strpos($key, 'field_id_') !== false) {
                $fieldIds[] = $value;
            } else {
                $formFields[$key] = $value;
            }
        }

        // Sauvegarder les données dans la base de données
        foreach ($fieldIds as $index => $fieldId) {
            $fieldKey = array_keys($formFields)[$index]; // Obtenir la clé du champ
            $fieldValue = $formFields[$fieldKey]; // Obtenir la valeur correspondante

            if (is_null($fieldValue)) {
                continue;
            }

            if (is_array($fieldValue)) {
                $fieldValue = json_encode($fieldValue);
            }

            // Insertion dans la table "reponses_formulaires"
            ReponseAvis::create([
                'sender_no' => $numero,
                'reponse' => $fieldValue,
                'agence' => Agence::where('libelle', $nom)->first()->id,
                'adresse_ip' => $this->getClientIPaddress(),
                'id_champs' => $fieldId,
                'status' => 1,
            ]);
        }

        return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);

    }

    public function saveReclamation(Request $request, $nom)
    {

        $numero = ReponseReclamation::all()->groupBy('sender_no')->count() + 1;

        $fields = $request->except('_token', 'field_id');
        $field_ids = $request->input('field_id');

//        dd($fields, $field_ids);

        foreach ($fields as $key => $value) {
            $field_id = array_shift($field_ids); // Extract the field id

            // Skip if value is null or empty
            if (empty($value)) {
                continue;
            }

//            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
//                Mail::to($value)->send(new ConfirmationReception($value));
//            }

            // Handle case where value is an array (e.g., for checkboxes)
            if (is_array($value)) {
                $value = json_encode($value);
            }

            // Insert or update the value in the database
            ReponseReclamation::create([
                'sender_no' => $numero,
                'reponse' => $value,
                'agence' => Agence::where('libelle', $nom)->first()->id,
                'adresse_ip' => $this->getClientIPaddress(),
                'id_champs' => $field_id,
                'status' => 1,
            ]);
        }


        return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function saveFeedback(Request $request, $nom, $module)
    {
        if ($request->feedback === 'like') {
            $agence_id = Agence::where('libelle', $nom)->first()->id;
            $cons = Consultation::where('agences_id', $agence_id)->where('module', $module)->first();

            $visite = $cons ? $cons->interesse : null;

            $consultation = Consultation::firstOrNew(['agences_id' => $agence_id, 'module' => $module, 'ip_address' => $this->getClientIPaddress()]);
            $consultation->agences_id = $agence_id;
            $consultation->ip_address = $this->getClientIPaddress();
            $consultation->interesse = $visite + 1;
            $consultation->save();

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        } elseif ($request->feedback ===  'dislike') {

            $agence_id = Agence::where('libelle', $nom)->first()->id;
            $cons = Consultation::where('agences_id', $agence_id)->where('module', $module)->first();

            $visite = $cons ? $cons->pas_interesse : null;

            $consultation = Consultation::firstOrNew(['agences_id' => $agence_id, 'module' => $module, 'ip_address' => $this->getClientIPaddress()]);
            $consultation->agences_id = $agence_id;
            $consultation->ip_address = $this->getClientIPaddress();
            $consultation->pas_interesse = $visite + 1;
            $consultation->save();

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        }


    }

    public function saveFaqLike(Request $request, $nom, $id)
    {
        if ($request->feedback === 'like') {
            $agence_id = Agence::where('libelle', $nom)->first()->id;
            $like = FaqStatistiques::where('agence_id', $agence_id)->where('faq_no', $id)->first();

            $visite = $like ? $like->likes : null;

            $consultation = FaqStatistiques::firstOrNew(['agence_id' => $agence_id, 'faq_no' => $id]);
            $consultation->agence_id = $agence_id;
            $consultation->faq_no = $id;
            $consultation->likes = $visite + 1;
            $consultation->save();

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        } elseif ($request->feedback ===  'dislike') {

            $agence_id = Agence::where('libelle', $nom)->first()->id;
            $like = FaqStatistiques::where('agence_id', $agence_id)->where('faq_no', $id)->first();

            $visite = $like ? $like->dislikes : null;

            $consultation = FaqStatistiques::firstOrNew(['agence_id' => $agence_id, 'faq_no' => $id]);
            $consultation->agence_id = $agence_id;
            $consultation->faq_no = $id;
            $consultation->dislikes = $visite + 1;
            $consultation->save();


            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        }
    }

    /**
     * @return mixed
     */
    static function getClientIPaddress()
    {

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $clientIp = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $clientIp = $forward;
        } else {
            $clientIp = $remote;
        }

        return $clientIp;
    }

}
