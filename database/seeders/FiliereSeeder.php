<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = [
            'Informatique de Gestion',
            'Planification et Gestion des Projets',
            'Statistique et Démographie',
        ];

        foreach ($filieres as $libelle) {
            Filiere::firstOrCreate(['libelle' => $libelle]);
        }
    }
}