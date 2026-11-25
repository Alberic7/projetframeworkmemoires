<?php

namespace Database\Factories;

use App\Models\AnneeAcademique;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemoireFactory extends Factory
{
    public function definition(): array
    {
        $statut = fake()->randomElement(['soumis', 'en_verification', 'valide', 'rejete']);

        return [
            'titre' => fake()->sentence(8),
            'resume' => fake()->paragraphs(2, true),
            'mots_cles' => implode(', ', fake()->words(4)),
            'encadreur' => 'Dr. ' . fake()->name(),
            'fichier_pdf' => 'memoires/exemple_memoire.pdf',
            'statut' => $statut,
            'motif_rejet' => $statut === 'rejete' ? 'Bibliographie incomplète et résumé trop court.' : null,
            'telechargement_autorise' => true,
            'acces_restreint' => false,
            'date_embargo' => null,
            'score_similarite' => fake()->randomFloat(2, 0, 25),
            'etudiant_id' => User::where('role', 'etudiant')->inRandomOrder()->value('id') ?? User::factory(),
            'filiere_id' => Filiere::inRandomOrder()->value('id'),
            'annee_academique_id' => AnneeAcademique::inRandomOrder()->value('id'),
        ];
    }
}