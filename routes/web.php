<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Gestionnaire;
use App\Http\Middleware\GestionnnaireOuBibliothecaire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\AccueilController::class, 'home'])->name('home');
Route::get('apropos', [App\Http\Controllers\AccueilController::class, 'apropos']);
Route::get('categories', [App\Http\Controllers\AccueilController::class, 'categories']);
Route::get('contact', [App\Http\Controllers\AccueilController::class, 'contact']);
Route::get('categorie/{categorie}', [App\Http\Controllers\AccueilController::class, 'categorie'])->name('categorie');
Route::get('resume/{id}', [App\Http\Controllers\AccueilController::class, 'resume'])->name('resume');



Auth::routes();
Route::group(['middleware' => ['auth']], function()
{
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('users', App\Http\Controllers\UserController::class)->middleware(Gestionnaire::Class);//getionnaire middleware
    Route::resource('livres', App\Http\Controllers\LivreController::class);
    Route::get('livrescsv', [App\Http\Controllers\LivreController::class, 'livrescsv'])->name('livrescsv');
    Route::post('download-csv-livres', [App\Http\Controllers\LivreController::class, 'exportCsv'])->name('download-csv-livres');
    Route::resource('emprunts', App\Http\Controllers\EmpruntController::class)->middleware(GestionnnaireOuBibliothecaire::class);
});