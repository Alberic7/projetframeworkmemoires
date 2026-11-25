<?php

use App\Http\Controllers\Admin\AnneeAcademiqueController;
use App\Http\Controllers\Admin\FiliereController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\ChefDepartementController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\MemoireController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', fn () => redirect()->route('catalogue.index'));
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/catalogue/{memoire}', [CatalogueController::class, 'show'])->name('catalogue.show');

// Démonstration du Layout Front
Route::get('/home', fn () => view('pages.home'))->name('home');

// Connecté (tous rôles)
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/memoires/{memoire}/lire', [MemoireController::class, 'lire'])->name('memoires.lire');
    Route::get('/memoires/{memoire}/pdf', [MemoireController::class, 'pdf'])->name('memoires.pdf');
    Route::get('/memoires/{memoire}/telecharger', [MemoireController::class, 'telecharger'])->name('memoires.telecharger');

    Route::get('/favoris', [FavoriController::class, 'index'])->name('favoris.index');
    Route::post('/favoris/{memoire}', [FavoriController::class, 'toggle'])->name('favoris.toggle');
    Route::get('/historique', [HistoriqueController::class, 'index'])->name('historique.index');
});

// Étudiant
Route::middleware(['auth', 'role:etudiant'])->group(function () {
    Route::get('/memoires/create', [MemoireController::class, 'create'])->name('memoires.create');
    Route::post('/memoires', [MemoireController::class, 'store'])->name('memoires.store');
    Route::get('/mes-memoires', [MemoireController::class, 'mesMemoires'])->name('mes-memoires');
});

// Responsable de la bibliothèque
Route::middleware(['auth', 'role:responsable'])->prefix('validation')->name('validation.')->group(function () {
    Route::get('/', [ValidationController::class, 'index'])->name('index');
    Route::get('/{memoire}', [ValidationController::class, 'show'])->name('show');
    Route::post('/{memoire}/valider', [ValidationController::class, 'valider'])->name('valider');
    Route::post('/{memoire}/rejeter', [ValidationController::class, 'rejeter'])->name('rejeter');
});

// Chef de département
Route::middleware(['auth', 'role:chef_departement'])->get('/filiere/memoires', [ChefDepartementController::class, 'index'])->name('filiere.memoires');

// Administrateur
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', Admin\UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('filieres', Admin\FiliereController::class)->except(['create', 'show', 'edit']);
    Route::resource('annees', Admin\AnneeAcademiqueController::class)->except(['create', 'show', 'edit'])
        ->parameters(['annees' => 'annee']);
    Route::get('statistiques', Admin\StatistiqueController::class)->name('statistiques');
});

require __DIR__.'/auth.php';
