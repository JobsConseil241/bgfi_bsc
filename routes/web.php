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
Route::post('/dashboard/faq-manage/add', [FaqController::class, 'store'])->name('addFAQs');
Route::post('/dashboard/faq-manage/add-reponses', [ReponseFAQController::class, 'store'])->name('AddReponses');

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
