<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChampFormulaireController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FormulaireController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OptionChampController;
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
Route::get('/agence/{nom}/reclamation', [WelcomeController::class, 'indexReclamation'])->name('welcomeReclamation');

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

Route::get('/dashboard/users-manage', [AdminController::class, 'indexUsers'])->name('indexUsers');


Route::get('/dashboard/faq-manage', [FaqController::class, 'index'])->name('indexFAQs');
Route::get('/dashboard/faq-manage/{id}/reponses', [ReponseFAQController::class, 'index'])->name('indexReponses');
Route::post('/dashboard/faq-manage/{id}/reponses', [ReponseFAQController::class, 'store'])->name('AddReponses');
Route::post('/dashboard/faq-manage/{id}/reponses/edit', [ReponseFAQController::class, 'edit'])->name('EditResponses');


Route::post('/dashboard/faq-manage/add', [FaqController::class, 'store'])->name('addFAQs');
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
