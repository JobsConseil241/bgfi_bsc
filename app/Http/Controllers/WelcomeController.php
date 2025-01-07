<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationReception;
use App\Mail\SendAvisMail;
use App\Mail\SendReclamationMail;
use App\Models\Agence;
use App\Models\ChampFormulaire;
use App\Models\Consultation;
use App\Models\Faq;
use App\Models\FaqStatistiques;
use App\Models\Formulaire;
use App\Models\Marketing;
use App\Models\ReponseAvis;
use App\Models\ReponseFaq;
use App\Models\ReponseReclamation;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WelcomeController extends Controller
{
    public function index(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();
        $param = Setting::where('agence_id', $agence->id)->first();
        $market = Marketing::where('active', 1)->latest('created_at')->get();

        return view('welcome', compact('agence', 'param', 'market'));
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function indexFAQ(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();
        $param = Setting::where('agence_id', $agence->id)->first();
        $Faq = Faq::all();

        foreach ($Faq as $faq) {
            $faq['reponses'] = ReponseFaq::where('id_faq', $faq->id)->get();
        }

        $agence_id = Agence::where('libelle', $nom)->first()->id;
        $cons = Consultation::where('agences_id', $agence_id)->where('module', 'faq')->first();

        $constr = Consultation::create([
            'agences_id' => $agence_id,
            'module' => 'faq',
            'visite' => 1,
            'ip_address' => $this->getClientIPaddress()
        ]);


        return view('dashboard.faq.view', compact('agence', 'Faq'));
    }

    public function indexConsulter(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();
        $param = Setting::where('agence_id', $agence->id)->first();

        $agence_id = Agence::where('libelle', $nom)->first()->id;


        return view('dashboard.formulaire.consultationFormView', compact('agence'));
    }
    public function retournevalue(Request $request, $nom) {

        // requetes à BGFi
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function indexAvis(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        $form = Formulaire::where('status', 1)->where('type', 'avis')->first();

        // Récupérer les champs et leurs options associés via Eloquent
        $formFields = ChampFormulaire::with('options')
            ->where('id_formulaire', $form->id)
            ->where('status', 1)
            ->orderBy('position', 'asc')
            ->get();

        $agence_id = Agence::where('libelle', $nom)->first()->id;
        $cons = Consultation::where('agences_id', $agence_id)->where('module', 'avis')->first();

        $constr = Consultation::create([
            'agences_id' => $agence_id,
            'module' => 'avis',
            'visite' => 1,
            'ip_address' => $this->getClientIPaddress()
        ]);

        // Passer les champs à la vue
        return view('dashboard.formulaire.avisFormView', compact('formFields', 'agence'));
    }
    public function indexReclamation(Request $request, $nom) {

        $agence  = Agence::where('libelle', $nom)->first();

        $form = Formulaire::where('status', 1)->where('type', 'reclamation')->first();

        // Récupérer les champs et leurs options associés via Eloquent
        $fields = ChampFormulaire::with('options')
            ->where('id_formulaire', $form->id)
            ->orderBy('position', 'asc')
            ->get();

        $agence_id = Agence::where('libelle', $nom)->first()->id;
        $cons = Consultation::where('agences_id', $agence_id)->where('module', 'reclamation')->first();

        $constr = Consultation::create([
            'agences_id' => $agence_id,
            'module' => 'reclamation',
            'visite' => 1,
            'ip_address' => $this->getClientIPaddress()
        ]);

        // Passer les champs à la vue
        return view('dashboard.formulaire.reclamationFormView', compact('fields', 'agence'));
    }

    /**
     * @param Request $request
     * @param $nom
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAvis(Request $request, $nom) {
        $data = $request->all();

        $data_array = [];

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
                $fieldValue = '-';
            }

            if (is_array($fieldValue)) {
                $fieldValue = json_encode($fieldValue);
            }

            // Insertion dans la table "reponses_formulaires"
            $data_array[$fieldKey] = $fieldValue;

            ReponseAvis::create([
                'sender_no' => $numero,
                'reponse' => $fieldValue,
                'agence' => Agence::where('libelle', $nom)->first()->id,
                'adresse_ip' => $this->getClientIPaddress(),
                'id_champs' => $fieldId,
                'status' => 1,
            ]);
        }

        // TODO : send mail on all responsable users

//        $users = User::where('role', 'responsable')->get();
//
//        foreach ($users as $user) {
//            Mail::to($user->email)->send(new SendAvisMail($data_array, $user));
//        }

        return response()->json(['status' => 200, 'success' => 'Formulaire soumis avec succès !']);

    }

    public function saveReclamation(Request $request, $nom)
    {

        $numero = ReponseReclamation::all()->groupBy('sender_no')->count() + 1;

        $fields = $request->except('_token', 'field_id');
        $field_ids = $request->input('field_id');

        $data_array = [];

//        dd($fields, $field_ids);

        foreach ($fields as $key => $value) {
            $field_id = array_shift($field_ids); // Extract the field id

            // Skip if value is null or empty
            if (empty($value)) {
                $value = '-';
            }

//            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
//                Mail::to($value)->send(new ConfirmationReception($value));
//            }

            // Handle case where value is an array (e.g., for checkboxes)
            if (is_array($value)) {
                $value = json_encode($value);
            }

            // Insertion dans la table "reponses_formulaires"
            $data_array[$key] = $value;

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

//        $users = User::where('role', 'responsable')->get();
//
//        foreach ($users as $user) {
//            Mail::to($user->email)->send(new SendReclamationMail($data_array, $user));
//        }

        Log::info($data_array);

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

            $consultation = Consultation::create([
                'agences_id' => $agence_id,
                'module' => $module,
                'visite' => 1,
                'interesse' => 1,
                'ip_address' => $this->getClientIPaddress()
            ]);

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        } elseif ($request->feedback ===  'dislike') {

            $agence_id = Agence::where('libelle', $nom)->first()->id;

            $consultation = Consultation::create([
                'agences_id' => $agence_id,
                'module' => $module,
                'visite' => 1,
                'pas_interesse' => 1,
                'ip_address' => $this->getClientIPaddress()
            ]);

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        }elseif ($request->feedback ===  'view') {

            $agence_id = Agence::where('libelle', $nom)->first()->id;

            $consultation = Consultation::create([
                'agences_id' => $agence_id,
                'module' => $module,
                'visite' => 1,
                'ip_address' => $this->getClientIPaddress()
            ]);

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        }


    }

    public function saveFaqLike(Request $request, $nom, $id)
    {
        if ($request->feedback === 'like') {
            $agence_id = Agence::where('libelle', $nom)->first()->id;

            $consultation = FaqStatistiques::create([
                'agence_id' => $agence_id,
                'faq_no' => $id,
                'likes' => 1,
                'ip_adress' => $this->getClientIPaddress()
            ]);

            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        } elseif ($request->feedback ===  'dislike') {

            $agence_id = Agence::where('libelle', $nom)->first()->id;

            $consultation = FaqStatistiques::create([
                'agence_id' => $agence_id,
                'faq_no' => $id,
                'dislikes' => 1,
                'ip_adress' => $this->getClientIPaddress()
            ]);


            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !']);
        } elseif ($request->feedback ===  'view') {

            $agence_id = Agence::where('libelle', $nom)->first()->id;

            $consultation = FaqStatistiques::create([
                'agence_id' => $agence_id,
                'faq_no' => $id,
                'view' => 1,
                'ip_adress' => $this->getClientIPaddress()
            ]);


            return response()->json(['status' => 200, 'success' => 'Avis soumis avec succès !', 'data' => 'view']);
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
