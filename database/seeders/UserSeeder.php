<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $filiereIG = Filiere::where('libelle', 'Informatique de Gestion')->first();
        $filierePGP = Filiere::where('libelle', 'Planification et Gestion des Projets')->first();

        // 1. Administrateur
        User::firstOrCreate(
            ['email' => 'admin@eneam.bj'],
            [
                'nom' => 'ADMIN',
                'prenom' => 'Système',
                'matricule' => null,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'filiere_id' => null,
            ]
        );

        // 2. Responsable de la bibliothèque (validateur)
        User::firstOrCreate(
            ['email' => 'responsable@eneam.bj'],
            [
                'nom' => 'KOUASSI',
                'prenom' => 'Marc',
                'matricule' => null,
                'password' => Hash::make('password'),
                'role' => 'responsable',
                'filiere_id' => null,
            ]
        );

        // 3. Chef de département (rattaché à Informatique de Gestion)
        User::firstOrCreate(
            ['email' => 'chef.ig@eneam.bj'],
            [
                'nom' => 'MENSAH',
                'prenom' => 'Alain',
                'matricule' => null,
                'password' => Hash::make('password'),
                'role' => 'chef_departement',
                'filiere_id' => $filiereIG?->id,
            ]
        );

        // 4. Étudiant principal de test (connexion par matricule ou email)
        User::firstOrCreate(
            ['email' => 'etudiant@eneam.bj'],
            [
                'nom' => 'DOSSOUMOUN',
                'prenom' => 'Paul-Hénoc',
                'matricule' => '20240001',
                'password' => Hash::make('password'),
                'role' => 'etudiant',
                'filiere_id' => $filiereIG?->id,
            ]
        );

        // 5. Quelques étudiants supplémentaires via Factory
        User::factory()->count(4)->create([
            'role' => 'etudiant',
            'filiere_id' => $filierePGP?->id,
        ]);
    }
}