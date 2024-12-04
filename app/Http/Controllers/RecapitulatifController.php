<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Agence;
use App\Models\ChampFormulaire;
use App\Models\Formulaire;
use App\Models\ReponseAvis;
use Illuminate\Http\Request;

class RecapitulatifController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function indexAvisData () {
        $responses = DB::table('reponse_avis')
            ->join('champ_formulaires', 'reponse_avis.id_champs', '=', 'champ_formulaires.id')
            ->select('reponse_avis.sender_no', 'reponse_avis.id_champs', 'reponse_avis.reponse', 'reponse_avis.agence', 'champ_formulaires.intitulé', 'champ_formulaires.name')
            ->get();

        // Grouper par `sender_no` et organiser par libellé
        $groupedResponses = $responses->groupBy('sender_no')->map(function ($group) {
            $formatted = [];
            $agence = '';
            foreach ($group as $response) {
                $formatted[$response->name] = $response->reponse;
                $agence = $response->agence;
            }
            $formatted["agence"] = Agence::where('id', $agence)->first()->libelle;
            return $formatted;
        });

        // Récupérer tous les libellés pour les colonnes
//        $columns = $responses->pluck('name')->unique();
        $columns = $responses->pluck('name', 'intitulé')->unique()->map(function ($name, $intitule) {
            return [
                'data' => $name, // Transformer en clé unique, ex. "nom_utilisateur"
                'title' => $intitule
            ];
        })->unique();
        $columns->push((object)[
            'data' => 'agence',
            'title' => 'Agence'
        ]);
//        $columns = array_push($columns, (object)[
//            'data' => 'agence',
//            'title' => 'Agence'
//        ]);

        return response()->json([
            'columns' => $columns->values(),
            'data' => $groupedResponses
        ]);

    }

    public function indexReclamationData () {
        // Récupérer tous les champs disponibles
        $fields = DB::table('champ_formulaires')
            ->select('id', 'name', 'intitulé')
            ->where('id_formulaire', '=', 2)
            ->get();

        // Récupérer les réponses avec les champs associés
        $responses = DB::table('reponse_reclamations')
            ->select('sender_no', 'id_champs', 'reponse')
            ->get();

        // Regrouper les réponses par `sender_no`
        $groupedResponses = $responses->groupBy('sender_no');

//        dd($groupedResponses);


        // Construire les données finales
        $data = $groupedResponses->map(function ($responses, $senderNo) use ($fields) {
            $result = ['sender_no' => $senderNo];

            // Ajouter chaque champ avec la réponse correspondante ou un tiret
            foreach ($fields as $field) {
                $response = $responses->firstWhere('id_champs', $field->id);
                $result[$field->name] = $response ? $response->reponse : '-';
            }

            return $result;
        })->values();

        // Préparer les colonnes (name et intitulé)
        $columns = $fields->map(function ($field) {
            return [
                'data' => $field->name,
                'title' => $field->intitulé
            ];
        });


//        $groupedResponses = $responses->groupBy('sender_no')->map(function ($group) use ($responses) {
//            $formatted = [];
//            $agence = '';
//
//            // Lister tous les champs possibles pour garantir qu'ils sont inclus
//            $fields = $responses->pluck('name')->unique();
//
//            foreach ($fields as $field) {
//                $fieldValue = $group->firstWhere('name', $field)?->reponse ?? '-'; // Valeur ou "-"
//                $formatted[$field] = $fieldValue;
//
//            }
//            return $formatted;
//        });
//
//        // Récupérer tous les libellés pour les colonnes
////        $columns = $responses->pluck('name')->unique();
//        $columns = $responses->pluck('name', 'intitulé')->unique()->map(function ($name, $intitule) {
//            return [
//                'data' => $name, // Transformer en clé unique, ex. "nom_utilisateur"
//                'title' => $intitule
//            ];
//        })->unique();
//        $columns->push((object)[
//            'data' => 'agence',
//            'title' => 'Agence'
//        ]);
//        $columns = array_push($columns, (object)[
//            'data' => 'agence',
//            'title' => 'Agence'
//        ]);
        return response()->json([
            'columns' => $columns,
            'data' => $data
        ]);

    }

    public function indexAvis () {
        $title = 'Recapitulatif';
        $rcprcl = 'active';
        $formsz = 'open';

        return view('dashboard.recapitulatifs.indexAvis', compact('title', 'rcprcl', 'formsz'));

    }

    public function indexReclamation () {
        $title = 'Recapitulatif';
        $rcpavis = 'active';
        $formsz = 'open';

        return view('dashboard.recapitulatifs.indexReclamation', compact('title', 'rcpavis', 'formsz'));

    }
}
