<?php

namespace Database\Seeders;

use App\Models\AnneeAcademique;
use App\Models\Memoire;
use App\Models\User;
use App\Models\Validation;
use Illuminate\Database\Seeder;

class MemoireSeeder extends Seeder
{
    public function run(): void
    {
        $etudiants = User::where('role', 'etudiant')->get();
        $responsable = User::where('role', 'responsable')->first();
        $annee2026 = AnneeAcademique::where('libelle', '2025-2026')->first();
        $annee2025 = AnneeAcademique::where('libelle', '2024-2025')->first();

        // 1. Mémoire validé standard (Étudiant 1 - Informatique de Gestion)
        $etu1 = $etudiants[0];
        $memoireValide = Memoire::create([
            'titre' => 'Conception et réalisation d\'une plateforme de gestion des mémoires de l\'ENEAM',
            'resume' => 'Ce mémoire présente l\'analyse, la modélisation UML et le développement sous Laravel d\'une bibliothèque numérique avec circuit de validation.',
            'mots_cles' => 'Laravel, bibliothèque numérique, ENEAM, mémoires',
            'encadreur' => 'Dr. AHOUANSOU Jean',
            'fichier_pdf' => 'memoires/memoire_valide_1.pdf',
            'statut' => 'valide',
            'telechargement_autorise' => true,
            'acces_restreint' => false,
            'score_similarite' => 8.50,
            'etudiant_id' => $etu1->id,
            'filiere_id' => $etu1->filiere_id,
            'annee_academique_id' => $annee2026->id,
        ]);

        Validation::create([
            'memoire_id' => $memoireValide->id,
            'responsable_id' => $responsable->id,
            'decision' => 'valide',
            'motif' => null,
        ]);

        // 2. Mémoire validé sans téléchargement (Étudiant 2 - Planification et Gestion des Projets)
        $etu2 = $etudiants[1];
        $memoireSansTelechargement = Memoire::create([
            'titre' => 'Optimisation des flux logistiques portuaires à Cotonou par analyse prédictive',
            'resume' => 'Étude des goulots d\'étranglement au Port Autonome de Cotonou et modélisation de scénarios d\'optimisation.',
            'mots_cles' => 'logistique, port de Cotonou, analyse prédictive',
            'encadreur' => 'Prof. GBAGUIDI Rodrigue',
            'fichier_pdf' => 'memoires/memoire_valide_2.pdf',
            'statut' => 'valide',
            'telechargement_autorise' => false,
            'acces_restreint' => false,
            'score_similarite' => 12.00,
            'etudiant_id' => $etu2->id,
            'filiere_id' => $etu2->filiere_id,
            'annee_academique_id' => $annee2025->id,
        ]);

        Validation::create([
            'memoire_id' => $memoireSansTelechargement->id,
            'responsable_id' => $responsable->id,
            'decision' => 'valide',
            'motif' => null,
        ]);

        // 3. Mémoire soumis (Étudiant 3)
        $etu3 = $etudiants[2];
        Memoire::create([
            'titre' => 'Système de détection de fraude bancaire par apprentissage automatique',
            'resume' => 'Implémentation d\'algorithmes de classification supervisée pour détecter les transactions suspectes en temps réel.',
            'mots_cles' => 'machine learning, fraude bancaire, classification',
            'encadreur' => 'Dr. TOSSOU Mireille',
            'fichier_pdf' => 'memoires/memoire_soumis.pdf',
            'statut' => 'soumis',
            'score_similarite' => 5.20,
            'etudiant_id' => $etu3->id,
            'filiere_id' => $etu3->filiere_id,
            'annee_academique_id' => $annee2026->id,
        ]);

        // 4. Mémoire en cours de vérification (Étudiant 4)
        $etu4 = $etudiants[3];
        Memoire::create([
            'titre' => 'Audit de sécurité des applications web institutionnelles au Bénin',
            'resume' => 'Évaluation des vulnérabilités OWASP Top 10 sur un échantillon de portails publics et recommandations de sécurisation.',
            'mots_cles' => 'cybersécurité, OWASP, audit web',
            'encadreur' => 'Dr. AHOUANSOU Jean',
            'fichier_pdf' => 'memoires/memoire_verification.pdf',
            'statut' => 'en_verification',
            'score_similarite' => 18.40,
            'etudiant_id' => $etu4->id,
            'filiere_id' => $etu4->filiere_id,
            'annee_academique_id' => $annee2026->id,
        ]);

        // 5. Mémoire rejeté avec motif (Étudiant 5)
        $etu5 = $etudiants[4];
        $memoireRejete = Memoire::create([
            'titre' => 'Gestion informatisée d\'une pharmacie de quartier',
            'resume' => 'Application de gestion des stocks et ventes de médicaments.',
            'mots_cles' => 'pharmacie, stock, gestion',
            'encadreur' => 'M. ZINSOU Brice',
            'fichier_pdf' => 'memoires/memoire_rejete.pdf',
            'statut' => 'rejete',
            'motif_rejet' => 'Le résumé est trop succinct et le document PDF ne contient pas la page de garde officielle de l\'ENEAM.',
            'score_similarite' => 42.00,
            'etudiant_id' => $etu5->id,
            'filiere_id' => $etu5->filiere_id,
            'annee_academique_id' => $annee2026->id,
        ]);

        Validation::create([
            'memoire_id' => $memoireRejete->id,
            'responsable_id' => $responsable->id,
            'decision' => 'rejete',
            'motif' => $memoireRejete->motif_rejet,
        ]);
    }
}