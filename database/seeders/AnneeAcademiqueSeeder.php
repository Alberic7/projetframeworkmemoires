<?php

namespace Database\Seeders;

use App\Models\AnneeAcademique;
use Illuminate\Database\Seeder;

class AnneeAcademiqueSeeder extends Seeder
{
    public function run(): void
    {
        $annees = [
            '2024-2025',
            '2025-2026',
        ];

        foreach ($annees as $libelle) {
            AnneeAcademique::firstOrCreate(['libelle' => $libelle]);
        }
    }
}