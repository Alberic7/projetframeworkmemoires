@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Titre de page -->
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Tableau de bord
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Bienvenue sur la plateforme de gestion des mémoires académiques.
            </p>
        </div>
        <div>
            <a href="{{ route('memoires.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-brand-600 transition">
                <x-svg.plus class="size-4" />
                Déposer un mémoire
            </a>
        </div>
    </div>

    <!-- 4 Cartes de statistiques -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Carte 1 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mémoires</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">24</h3>
                </div>
                <div class="flex size-12 items-center justify-center rounded-xl bg-brand-50 text-brand-500 dark:bg-brand-500/15">
                    <x-svg.database class="size-6" />
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs text-success-600 font-medium">
                <x-svg.arrow-up class="size-3.5" />
                <span>+12% par rapport au semestre passé</span>
            </div>
        </div>

        <!-- Carte 2 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">En attente</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">5</h3>
                </div>
                <div class="flex size-12 items-center justify-center rounded-xl bg-orange-50 text-orange-500 dark:bg-orange-500/15">
                    <x-svg.time class="size-6" />
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                À valider par la bibliothèque
            </div>
        </div>

        <!-- Carte 3 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Validés</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">19</h3>
                </div>
                <div class="flex size-12 items-center justify-center rounded-xl bg-success-50 text-success-500 dark:bg-success-500/15">
                    <x-svg.check-circle class="size-6" />
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                Disponibles en consultation publique
            </div>
        </div>

        <!-- Carte 4 -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Consultations</span>
                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">342</h3>
                </div>
                <div class="flex size-12 items-center justify-center rounded-xl bg-blue-light-50 text-blue-light-500 dark:bg-blue-light-500/15">
                    <x-svg.eye class="size-6" />
                </div>
            </div>
            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                Lectures et téléchargements
            </div>
        </div>
    </div>

    <!-- Tableau des Mémoires Récents -->
    <div class="rounded-2xl border border-gray-200 bg-white shadow-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <div>
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Derniers mémoires déposés</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Vue d'ensemble des dernières soumissions étudiantes</p>
            </div>
            <a href="{{ route('catalogue.index') }}" class="text-xs font-semibold text-brand-500 hover:text-brand-600">
                Voir tout le catalogue &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Titre du mémoire</th>
                        <th class="px-6 py-3 font-semibold">Auteur / Filière</th>
                        <th class="px-6 py-3 font-semibold">Année</th>
                        <th class="px-6 py-3 font-semibold">Statut</th>
                        <th class="px-6 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            Conception d'une plateforme cloud sécurisée pour la télémédecine
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">Koffi Jean</div>
                            <div class="text-xs text-gray-500">Génie Logiciel</div>
                        </td>
                        <td class="px-6 py-4">2025-2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-400">
                                Validé
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-medium text-brand-500 hover:underline">Consulter</a>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            Optimisation des algorithmes de détection de fraudes bancaires par IA
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">Aminata Diallo</div>
                            <div class="text-xs text-gray-500">Intelligence Artificielle & Data</div>
                        </td>
                        <td class="px-6 py-4">2025-2026</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-0.5 text-xs font-medium text-orange-700 dark:bg-orange-500/15 dark:text-orange-400">
                                En attente
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-medium text-brand-500 hover:underline">Consulter</a>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            Audit et durcissement des infrastructures réseau d'entreprise
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">Paul Dosso</div>
                            <div class="text-xs text-gray-500">Sécurité & Réseaux</div>
                        </td>
                        <td class="px-6 py-4">2024-2025</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-success-50 px-2.5 py-0.5 text-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-400">
                                Validé
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="font-medium text-brand-500 hover:underline">Consulter</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
