<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChampFormulaireController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FormulaireController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\OptionChampController;
use App\Http\Controllers\PertinenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecapitulatifController;
use App\Http\Controllers\ReponseFAQController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/agence/{nom}', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/agence/{nom}/faq', [WelcomeController::class, 'indexFAQ'])->name('welcomeFAQ');
Route::get('/agence/{nom}/avis', [WelcomeController::class, 'indexAvis'])->name('welcomeAvis');
Route::post('/agence/{nom}/avis', [WelcomeController::class, 'saveAvis'])->name('saveAvis');
Route::get('/agence/{nom}/reclamation', [WelcomeController::class, 'indexReclamation'])->name('welcomeReclamation');
Route::post('/agence/{nom}/reclamation', [WelcomeController::class, 'saveReclamation'])->name('saveReclamation');


Route::post('/save-feedback/{nom}/{module}', [WelcomeController::class, 'saveFeedback'])->name('saveFeedback');
Route::post('/save-faq-feedback/{nom}/{faq}', [WelcomeController::class, 'saveFaqLike'])->name('saveFeedbackFaq');



Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


/***************************************************************************************************
 * ******************                   DASHBOARD ROUTE                       **********************
 **************************************************************************************************/

Route::get('/dashboard', [AdminController::class, 'index'])->name('indexDashboard');
Route::post('/', [AdminController::class, 'store'])->name('store');

Route::get('/dashboard/agences', [AgenceController::class, 'index'])->name('indexAgences');
Route::post('/dashboard/agences', [AgenceController::class, 'store'])->name('AddAgences');
Route::post('/dashboard/agences/modify', [AgenceController::class, 'update'])->name('ModifyAgences');
Route::post('/dashboard/agences/{id}/delete', [AgenceController::class, 'destroy'])->name('DeleteAgences');

Route::get('/dashboard/users-manage', [AdminController::class, 'indexUsers'])->name('indexUsers');
Route::post('/dashboard/users-manage', [AdminController::class, 'addUsers'])->name('addUsers');
Route::post('/dashboard/users-manage/edit', [AdminController::class, 'editUsers'])->name('editUsers');
Route::post('/dashboard/users-manage/{id}/delete', [AdminController::class, 'deleteUsers'])->name('deleteUsers');


Route::get('/dashboard/faq-manage', [FaqController::class, 'index'])->name('indexFAQs');
Route::get('/dashboard/faq-manage/{id}/reponses', [ReponseFAQController::class, 'index'])->name('indexReponses');
Route::post('/dashboard/faq-manage/{id}/reponses', [ReponseFAQController::class, 'store'])->name('AddReponses');
Route::post('/dashboard/faq-manage/{id}/reponses/edit', [ReponseFAQController::class, 'edit'])->name('EditResponses');
Route::post('/dashboard/faq-manage/{id}/reponses/delete', [ReponseFAQController::class, 'destroy'])->name('DeleteResponses');


Route::post('/dashboard/faq-manage/add', [FaqController::class, 'store'])->name('addFAQs');
Route::post('/dashboard/faq-manage/edit', [FaqController::class, 'update'])->name('EditFaqs');
Route::post('/dashboard/faq-manage/{id}/delete', [FaqController::class, 'delete'])->name('deleteFaqs');
Route::post('/dashboard/faq-manage/add-reponses', [ReponseFAQController::class, 'store'])->name('');

Route::get('/dashboard/page-manager', [SettingController::class, 'index'])->name('indexSetting');
Route::get('/dashboard/page-manager/agence', [SettingController::class, 'show'])->name('indexPageSetting');
Route::post('/dashboard/page-manager', [SettingController::class, 'store'])->name('saveSetting');
Route::post('/dashboard/page-manager/FAQ', [SettingController::class, 'storeFaq'])->name('saveSettingFaq');
Route::post('/dashboard/page-manager/avis', [SettingController::class, 'storeAvis'])->name('saveSettingAvis');
Route::post('/dashboard/page-manager/reclamation', [SettingController::class, 'storeReclamation'])->name('saveSettingReclamation');
Route::post('/dashboard/page-manager/consultation', [SettingController::class, 'storeConsultation'])->name('saveSettingConsultation');

// avis gestion

Route::get('/dashboard/formulaire/avis', [FormulaireController::class, 'indexAvis'])->name('indexFormAvis');
Route::post('/dashboard/formulaire/avis', [FormulaireController::class, 'storeAvis'])->name('addFormAvis');
Route::post('/dashboard/formulaire/avis/edition', [FormulaireController::class, 'editAvis'])->name('editFormAvis');

Route::get('/dashboard/formulaire/avis/{id}', [ChampFormulaireController::class, 'indexAvise'])->name('indexFormAvise');
Route::post('/dashboard/formulaire/avis/{id}', [ChampFormulaireController::class, 'storeAvise'])->name('addFormAvise');
Route::post('/dashboard/formulaire/avis/{id}/edit', [ChampFormulaireController::class, 'editAvise'])->name('editFormAvise');

Route::get('/dashboard/formulaire/avis/{id}/options/{champ}', [optionChampController::class, 'indexChampOption'])->name('indexFormAvisChamp');
Route::post('/dashboard/formulaire/avis/{id}/options/{champ}', [optionChampController::class, 'storeChampOption'])->name('addFormAvisChamp');
Route::post('/dashboard/formulaire/avis/{id}/options/{champ}/edit', [optionChampController::class, 'editChampOption'])->name('editFormAvisChamp');

// avis reclamation


Route::get('/dashboard/formulaire/reclamation', [FormulaireController::class, 'indexRecla'])->name('indexFormRecla');
Route::post('/dashboard/formulaire/reclamation', [FormulaireController::class, 'storeRecla'])->name('addFormRecla');
Route::post('/dashboard/formulaire/reclamation/edition', [FormulaireController::class, 'editRecla'])->name('editFormRecla');

Route::get('/dashboard/formulaire/reclamation/{id}', [ChampFormulaireController::class, 'indexReclae'])->name('indexFormReclae');
Route::post('/dashboard/formulaire/reclamation/{id}', [ChampFormulaireController::class, 'storeReclae'])->name('addFormReclae');
Route::post('/dashboard/formulaire/reclamation/{id}/edit', [ChampFormulaireController::class, 'editReclae'])->name('editFormReclae');

Route::get('/dashboard/formulaire/reclamation/{id}/options/{champ}', [optionChampController::class, 'indexChampOptionRecla'])->name('indexFormReclaChamp');
Route::post('/dashboard/formulaire/reclamation/{id}/options/{champ}', [optionChampController::class, 'storeChampOptionRecla'])->name('addFormReclaChamp');
Route::post('/dashboard/formulaire/reclamation/{id}/options/{champ}/edit', [optionChampController::class, 'editChampOptionRecla'])->name('editFormReclaChamp');

// recapitulatifs
Route::get('/dashboard/recapitulatifs/avis/data', [RecapitulatifController::class, 'indexAvisData'])->name('RecapitulatifAvisData');
Route::get('/dashboard/recapitulatifs/reclamation/data', [RecapitulatifController::class, 'indexReclamationData'])->name('RecapitulatifReclaData');
Route::get('/dashboard/recapitulatifs/avis', [RecapitulatifController::class, 'indexAvis'])->name('RecapitulatifAvis');
Route::get('/dashboard/recapitulatifs/reclamations', [RecapitulatifController::class, 'indexReclamation'])->name('RecapitulatifReclamation');

// marketings
Route::get('/dashboard/marketing-manage', [MarketingController::class, 'index'])->name('marketing');
Route::post('/dashboard/marketing-manage/add', [MarketingController::class, 'create'])->name('marketingAdd');
Route::post('/dashboard/marketing-manage/{id}/delete', [MarketingController::class, 'delete'])->name('marketingDelete');
Route::get('/dashboard/marketing-manage/reload', [MarketingController::class, 'getVisits'])->name('marketingData');

// pertinences part
Route::get('/dashboard/pertinence/faq', [PertinenceController::class, 'indexFaq'])->name('faqStat');
Route::get('/dashboard/pertinence/faq/reload', [PertinenceController::class, 'getVisitsFaq'])->name('faqStatReload');
Route::get('/dashboard/pertinence/faq/reloads', [PertinenceController::class, 'getVisitsFaqs'])->name('faqStatReloads');
//Route::get('/dashboard/pertinence/consultation', [PertinenceController::class, 'indexConsultation'])->name('consultationStatReloads');
//Route::get('/dashboard/pertinence/faq/reloads', [PertinenceController::class, 'getVisitsFaqs'])->name('faqStatReloads');

// Profile Page
Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
Route::patch('/dashboard/profile/{id}/update', [ProfileController::class, 'update'])->name('profile.update');


//Clear Cache facade value:
Route::get('/key', function () {
    $exitCode = Artisan::call('key:generate');
    return '<h1>Key generated with success !</h1>';
});

//Clear Cache facade value:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cache cleared</h1>';
});

//Storage link:
Route::get('/link-storage', function () {
    $exitCode = Artisan::call('storage:link');
    return '<h1>Clear Config cache cleared</h1>';
});

//Clear Config cache:
Route::get('/proc-open-error', function () {
    $exitCode = Artisan::call('vendor:publish', ['--tag' => 'flare-config']);
    return '<h1>Proc open error resolved -> Think to change parameters in config/flare.php !!!</h1>';
});

//Storage route link
Route::get('/any-route', function () {
    $exitCode = Artisan::call('storage:link');
    echo $exitCode; // 0 exit code for no errors.
});
